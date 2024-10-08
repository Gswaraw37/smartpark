<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Midtrans\Snap;
use Midtrans\Config;
use App\Models\Payment;
use App\Models\Vehicle;
use App\Models\Approval;
use App\Models\BlockNumber;
use App\Models\VehicleType;
use Illuminate\Http\Request;
use App\Models\ParkingSession;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    public function __construct()
    {
        Config::$serverKey = config('midtrans.serverKey');
        Config::$isProduction = config('midtrans.isProduction');
        Config::$isSanitized = config('midtrans.isSanitized');
        Config::$is3ds = config('midtrans.is3ds');
    }

    public function showPayment($sessionId)
    {
        $parkingSession = ParkingSession::findOrFail($sessionId);
        $approval = Approval::where('vehicle_id', $parkingSession->vehicle_id)->latest()->first();
        $amount = $this->calculateParkingFee($approval);
        $user = Auth::user();
        $vehicle = Vehicle::where('user_id', $user->id)->first();
        $block = $approval ? BlockNumber::find($approval->block_id) : null;
        $payment = Payment::where('session_id', $sessionId)->latest()->first();
        $paymentStatus = $payment ? $payment->payment_status : 'Belum Dibayar';
        $snapToken = $payment->snap_token;

        return view('pengunjung.payment', compact([
            'parkingSession', 
            'approval', 
            'amount', 
            'snapToken', 
            'vehicle', 
            'block', 
            'paymentStatus'
        ]));
    }

    private function calculateParkingFee($approval)
    {
        if (!$approval) {
            return 0;
        }
    
        $vehicle = Vehicle::find($approval->vehicle_id);
        $entryTime = new Carbon($approval->entry_time);
        $exitTime = new Carbon($approval->exit_time);
        $duration = ceil($entryTime->diffInMinutes($exitTime) / 60);
    
        $rate = 0;
    
        if ($vehicle->vehicleType->name == 'Motor') {
            $rate = 3000;
            if ($duration > 1) {
                $rate += 2000 * ($duration - 1);
            }
        } elseif ($vehicle->vehicleType->name == 'Mobil') {
            $rate = 5000;
            if ($duration > 1) {
                $rate += 3000 * ($duration - 1);
            }
        }
    
        return $rate;
    }

    public function midtransCallback(Request $request)
    {
        $data = $request->all();

        $transactionStatus = $data['transaction_status'];
        $orderId = $data['order_id'];

        $payment = Payment::where('transaction_id', $orderId)->first();

        if ($payment) {
            if ($transactionStatus == 'settlement') {
                $payment->update(['payment_status' => 'Paid']);
            } elseif ($transactionStatus == 'cancel' || $transactionStatus == 'deny' || $transactionStatus == 'expire') {
                $payment->update(['payment_status' => 'Failed']);
            } elseif ($transactionStatus == 'pending') {
                $payment->update(['payment_status' => 'Pending']);
            }
        }

        return response()->json(['message' => 'Callback handled'], 200);
    }
}

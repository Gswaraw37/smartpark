<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Midtrans\Snap;
use App\Models\User;
use Midtrans\Config;
use App\Models\Payment;
use App\Models\Vehicle;
use App\Models\Approval;
use App\Models\BlockNumber;
use App\Models\VehicleType;
use App\Models\ParkingFloor;
use Illuminate\Http\Request;
use App\Models\ParkingSession;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class VisitorController extends Controller
{
    public function __construct()
    {
        Config::$serverKey = config('midtrans.serverKey');
        Config::$isProduction = config('midtrans.isProduction');
        Config::$isSanitized = config('midtrans.isSanitized');
        Config::$is3ds = config('midtrans.is3ds');
    }

    public function index()
    {
        $user = auth()->user();

        $vehicletype = VehicleType::all();
        $blocknumber = BlockNumber::where('is_occupied', false)->latest()->first();
        $vehicles = Vehicle::where('user_id', $user->id)->get();
        $vehicle = Vehicle::where('user_id', $user->id)->first();
        $approval = ParkingSession::where('user_id', $user->id)->latest()->first();
        $approvalStatus = $approval ? $approval->approval_status : null;
        $paymentStatus = 'Belum Dibayar';

        if ($vehicle) {
            $payment = Payment::with(['parkingsession' => function($query) use ($vehicle) {
                $query->where('vehicle_id', $vehicle->id);
            }])->latest()->first();
    
            if ($payment) {
                $paymentStatus = $payment->payment_status;
            }
        }
        
        $unreadNotifications = $user->unreadNotifications;

        if ($unreadNotifications->count() > 0) {
            $messages = [];
            foreach ($unreadNotifications as $notification) {
                $messages[] = $notification->data['message'];
                $notification->markAsRead();
            }
            session()->flash('messages', $messages);
        }

        return view('pengunjung.index', compact([
            'vehicletype',
            'blocknumber',
            'vehicles',
            'approvalStatus',
            'approval',
            'paymentStatus',
            'unreadNotifications',
        ]));
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'licence_plate' => 'required|unique:vehicles,licence_plate',
            'vehicle_type_id' => 'required|exists:vehicle_types,id',
        ]);
        $validatedData['user_id'] = Auth::user()->id;
        $validatedData['vehicle_type_id'] = $request->vehicle_type_id;

        Vehicle::create($validatedData);
        return redirect('/');
    }

    public function showProfile()
    {
        $user = User::findOrFail(Auth::id());

        return view('pengunjung.user.profile', compact('user'));
    }

    public function editProfile(Request $request, User $user)
    {
        $validatedData = $request->validate([
            'full_name' => 'required',
            'password' => 'required',
            'bio' => 'nullable|max:50',
            'foto' => 'nullable|file|mimes:jpg,png,jpeg,gif,svg|max:2048',
        ]);

        if($request->file('foto')){
            if($request->oldImage){
                Storage::delete($request->oldImage);
            }
            $validatedData['foto'] = $request->file('foto')->store('foto-profile');
        }

        if (!empty($validatedData['password'])) {
            $user->password = Hash::make($validatedData['password']);
        }

        $user->update($validatedData);

        return redirect('/profile')->with('success', 'Data Berhasil Diupdate');
    }

    public function update(Request $request, string $id)
    {
        //
    }

    public function destroy(string $id)
    {
        //
    }

    public function parkir()
    {
        $vehicletype = VehicleType::all();
        $vehicles = Vehicle::where('user_id', Auth::id())->get();
        $user = Auth::user();
        $vehicle = Vehicle::where('user_id', $user->id)->first();
        $approvalStatus = ParkingSession::where('user_id', $user->id)->latest()->value('approval_status');
        $approval = Approval::where('vehicle_id', $vehicle->id)->latest()->first();
        $block = BlockNumber::find($approval->block_id);
        $payment = Payment::with(['parkingsession' => function($query) use ($vehicle) {
            $query->where('vehicle_id', $vehicle->id);
        }])->latest()->first();
        $paymentStatus = 'Belum Dibayar';

        return view('pengunjung.parkir', compact([
            'vehicletype',
            'vehicles',
            'vehicle',
            'approvalStatus',
            'approval',
            'block',
            'paymentStatus',
        ]));
    }

    public function parkIn(Request $request)
    {
        // echo "Metode parkIn dipanggil";
        // dd('Metode parkIn dijalankan');
        // echo "Setelah dd";
        // die('Test');
        $user = Auth::user();
    
        $validatedData = $request->validate([
            'vehicle_id' => 'required|exists:vehicles,id',
            'block_id' => 'required|exists:block_numbers,id',
        ]);
        
        $vehicle = Vehicle::where('id', $validatedData['vehicle_id'])->where('user_id', $user->id)->first();
        $validatedData['entry_time'] = now();
        $validatedData['action'] = 'Entry';
        
        $block = BlockNumber::find($request->block_id);
        $floor = ParkingFloor::find($block->floor_id);

        if($floor->occupied_spots >= 50){
            return redirect('/parkir')->with('error', 'Parkir sudah penuh.');
        }
        
        $occupiedSpots = Approval::where('block_id', $block->id)->where('action', 'Entry')->count();
        
        if ($occupiedSpots < 10) {
            Approval::create(array_merge($validatedData, ['block_id' => $block->id]));

            $floor->occupied_spots += 1;
            $floor->save();
            
            if ($occupiedSpots + 1 >= 10) {
                $nextBlock = BlockNumber::where('floor_id', $block->floor_id)
                    ->where('block', '>', $block->block)
                    ->where('is_occupied', false)
                    ->orderBy('block')
                    ->first();
        
                if (!$nextBlock) {
                    $nextBlock = BlockNumber::where('floor_id', '>', $block->floor_id)
                        ->where('is_occupied', false)
                        ->orderBy('floor_id')
                        ->orderBy('block')
                        ->first();
                }
        
                if ($nextBlock) {
                    $nextBlock->is_occupied = true;
                    $nextBlock->save();
                }
            }
            
            return redirect('/parkir')->with('success', 'Kendaraan berhasil diparkir.');
        }
    }

    public function parkOut(Request $request)
    {
        $user = Auth::user();

        $validatedData = $request->validate([
            'vehicle_id' => 'required|exists:vehicles,id',
        ]);

        $vehicleId = $request->input('vehicle_id');
        $vehicle = Vehicle::where('id', $vehicleId)->where('user_id', $user->id)->latest()->first();

        if (!$vehicle) {
            return redirect('/parkir')->with('error', 'Kendaraan tidak ditemukan.');
        }

        $lastSpot = Approval::where('vehicle_id', $vehicle->id)->where('action', 'Entry')->latest()->first();

        if (!$lastSpot) {
            return redirect('/parkir')->with('error', 'Kendaraan tidak ditemukan dalam spot parkir.');
        }

        $parkingSession = ParkingSession::create([
            'user_id' => $user->id,
            'vehicle_id' => $vehicle->id,
            'spot_id' => $lastSpot->block_id,
            'approval_status' => 'Pending',
        ]);

        $amount = $this->calculateParkingFee($lastSpot);

        $payment = Payment::create([
            'session_id' => $parkingSession->id,
            'amount' => $amount,
            'payment_status' => 'Pending',
            'payment_method' => 'Midtrans',
            'transaction_id' => $lastSpot->id . '-' . time(),
        ]);

        $transactionDetails = [
            'order_id' => $payment->transaction_id,
            'gross_amount' => $amount,
        ];

        $customerDetails = [
            'first_name' => $user->username,
            'email' => $user->email,
        ];

        $params = [
            'transaction_details' => $transactionDetails,
            'customer_details' => $customerDetails,
        ];

        $snapToken = Snap::getSnapToken($params);
        $payment->snap_token = $snapToken;
        $payment->save();

        $lastSpot->update([
            'action' => 'Pending',
            'exit_time' => now(),
        ]);

        $block = BlockNumber::find($lastSpot->block_id);
        $floor = ParkingFloor::find($block->floor_id);
        $floor->occupied_spots -= 1;
        $floor->save();

        if(ParkingSession::where('user_id', auth()->id())->latest()->value('approval_status') == 'Pending'){
            return redirect()->route('payments.show', ['sessionId' => $parkingSession->id])->with('info', 'Silahkan Lakukan Pembayaran Terlebih Dahulu');
        }

        return redirect('/')->with('success', 'Permintaan keluar parkir Anda telah disetujui.');
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

    // public function handleRedirectAfterApproval()
    // {
    //     if(ParkingSession::where('user_id', auth()->id())->latest()->value('approval_status') == 'Pending'){
    //         return redirect('/parkir')->with('info', 'Permintaan keluar parkir telah diajukan. Menunggu persetujuan admin/staff.');
    //     }
    //     return redirect('/')->with('success', 'Permintaan keluar parkir Anda telah disetujui.');
    // }

    public function resubmitParkOut(Request $request)
    {
        $user = Auth::user();
    
        $validatedData = $request->validate([
            'vehicle_id' => 'required|exists:vehicles,id',
        ]);

        $vehicle = Vehicle::find($validatedData['vehicle_id']);

        if (!$vehicle) {
            return redirect('/parkir')->with('error', 'Kendaraan tidak ditemukan.');
        }

        $lastSpot = Approval::where('vehicle_id', $vehicle->id)->latest()->first();

        if (!$lastSpot) {
            return redirect('/parkir')->with('error', 'Kendaraan tidak ditemukan dalam spot parkir.');
        }

        $parkingSession = ParkingSession::updateOrCreate([
            'user_id' => $user->id,
            'vehicle_id' => $vehicle->id,
            'spot_id' => $lastSpot->block_id,
            'approval_status' => 'Rejected',
        ],[
            'approval_status' => 'Pending' 
        ]);
        
        $lastSpot->action = 'pending';
        $lastSpot->save();

        $payment = Payment::where('session_id', $parkingSession->id)->latest()->first();

        if ($payment && $payment->payment_status != 'Paid') {
            return redirect()->route('payments.show', ['sessionId' => $parkingSession->id])->with('info', 'Permintaan keluar parkir telah ditolak. Silakan lakukan pembayaran kembali.');
        }

        return redirect('/')->with('info', 'Permintaan keluar parkir telah diajukan kembali. Menunggu persetujuan admin.');
    }

    public function showLocaction()
    {
        $vehicletype = VehicleType::all();
        $user = Auth::user();
        $vehicle = Vehicle::where('user_id', $user->id)->first();
        $approval = Approval::where('vehicle_id', $vehicle->id)->latest()->first();
        $blocknumber = BlockNumber::with('parkingfloor')->latest()->paginate(3);

        return view('pengunjung.lokasi.index', compact([
            'vehicletype',
            'user',
            'vehicle',
            'approval',
            'blocknumber',
        ]));
    }
}

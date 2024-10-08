<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Staff;
use App\Models\Payment;
use App\Models\Approval;
use App\Models\BlockNumber;
use Illuminate\Http\Request;
use App\Exports\ExportSummary;
use App\Models\ParkingSession;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Notification;
use App\Notifications\ParkingApprovalNotification;

class AdminController extends Controller
{
    public function index()
    {
        $employees = User::where('role', 'staff')->count();
        $visitors = Approval::whereIn('action', ['Entry', 'Pending'])->count();
        $parkingSession = ParkingSession::where('approval_status', 'Pending')->with('user', 'vehicle', 'blocknumber')->get();

        return view('admin.dashboard', compact([
            'employees',
            'visitors',
            'parkingSession',
        ]));
    }

    public function accept(ParkingSession $parkingSession)
    {
        ParkingSession::where('id', $parkingSession->id)->update(array('approval_status' => 'Approved'));

        $approval = Approval::where('vehicle_id', $parkingSession->vehicle_id)->latest()->first();

        if ($approval) {
            $approval->update(['action' => 'Exit']);
            $block = BlockNumber::find($approval->block_id);
            if ($block) {
                $block->is_occupied = false;
                $block->save();
            }
        }

        Notification::send($parkingSession->user, new ParkingApprovalNotification('Approved'));
        return redirect('admin/dashboard')->with('success', 'Gate Terbuka');
    }

    public function decline(ParkingSession $parkingSession)
    {
        ParkingSession::where('id', $parkingSession->id)->update(array('approval_status' => 'Rejected'));

        Approval::where('id', $parkingSession->id)->update(array('action' => 'Entry'));

        Notification::send($parkingSession->user, new ParkingApprovalNotification('Rejected'));
        return redirect('admin/dashboard')->with('error', 'Gate Tidak Bisa Terbuka, Lakukan Pembayaran Terlebih Dahulu!');
    }

    public function showProfile()
    {
        $user = User::findOrFail(Auth::id());

        return view('admin.user.profile', compact('user'));
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

        return redirect('admin/profile')->with('success', 'Data Berhasil Diupdate');
    }

    public function showLoc()
    {
        $block = BlockNumber::with('parkingfloor')->latest()->get();

        return view('admin.daftar.lokasi.index', compact([
            'block',
        ]));
    }

    public function showDetail($id)
    {
        $parkingSession = ParkingSession::with('user', 'vehicle', 'blocknumber', 'payment')->findOrFail($id);
        $approval = Approval::findOrFail($id);

        return view('admin.detail.kendaraan', compact([
            'parkingSession',
            'approval',
        ]));
    }
}

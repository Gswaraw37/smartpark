<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Vehicle;
use App\Models\Approval;
use Illuminate\Http\Request;
use App\Models\ParkingSession;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use RealRashid\SweetAlert\Facades\Alert;

class AuthController extends Controller
{
    public function register(){
        $gender = ['L', 'P'];

        return view('auth.register', compact('gender'));
    }

    public function store(Request $request){
        $validatedData = $request->validate([
            'username' => 'required|unique:users',
            'full_name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed',
            'jk' => 'required',
        ]);
        $validatedData['password'] = Hash::make($validatedData['password']);
        $validatedData['role'] = 'pengunjung';
        unset($validatedData['password_confirmation']);

        User::create($validatedData);
        return redirect('/login')->with('success', 'Berhasil Membuat Akun');
    }
    
    public function login()
    {
        return view('auth.login');
    }

    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'username' => ['required'],
            'password' => ['required'],
        ]);
    
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
    
            $user = Auth::user();
    
            if ($user->role == 'admin' || $user->role == 'staff') {
                return redirect('/admin/dashboard')->with('success', 'Selamat Datang ' . $user->username);
            } elseif (Approval::where('vehicle_id', Vehicle::where('user_id', auth()->id())->value('id'))->latest()->value('action') === 'Entry') {
                return redirect('/parkir')->with('info', 'Anda masih dalam sesi parkir.');
            }

            if (ParkingSession::where('user_id', auth()->id())->latest()->value('approval_status') === 'Pending') {
                return redirect('/')->with('info', 'Anda masih menunggu konfirmasi dari admin/staff.');
            }
            return redirect('/')->with('success', 'Selamat Datang ' . $user->username);
        }
 
        Session::flash('status', 'failed');
        Session::flash('message', 'Login Gagal!');
        return redirect('/login')->with('error', 'Maaf Anda Belum Terdaftar di Sistem Kami');
    }

    public function logout(Request $request)
    {
        Auth::logout();
 
        $request->session()->invalidate();
    
        $request->session()->regenerateToken();
    
        return redirect('/login')->with('success', 'Anda Berhasil Logout, Hati - Hati di Jalan :)');
    }
}

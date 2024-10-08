<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class StaffController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $staff = User::where('role', 'staff')->get();
        $gender = ['L', 'P'];
        $role = ['staff', 'pengunjung'];
        
        return view('admin.daftar.staff.index', compact([
            'staff',
            'gender',
            'role',
        ]));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'username' => 'required|unique:users',
            'full_name' => 'required',
            'email' => 'required|email|unique:users',
            'jk' => 'required',
            'bio' => 'nullable|max:50',
            'foto' => 'nullable|file|mimes:jpg,png,jpeg,gif,svg|max:2048',
            'role' => 'required',
        ], [
            'username.required' => 'Username wajib diisi.',
            'username.unique' => 'Username sudah terdaftar.',
            'full_name.required' => 'Nama lengkap wajib diisi.',
            'jk.required' => 'Jenis kelamin wajib diisi.',
            'bio.max' => 'Bio tidak boleh lebih dari 50 karakter.',
            'foto.image' => 'Foto harus berupa gambar.',
            'foto.mimes' => 'Foto harus memiliki format jpg, png, jpeg, gif, atau svg.',
            'foto.max' => 'Ukuran foto tidak boleh lebih dari 2048 kilobyte.',
        ]);
        $validatedData['password'] = Hash::make('12345678');
        $validatedData['role'] = 'staff';

        if($request->file('foto')){
            $validatedData['foto'] = $request->file('foto')->store('foto-profile');
        }

        User::create($validatedData);
        return redirect('admin/daftar-staff')->with('success', 'Berhasil Menambahkan Staff');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $staff = User::find($id);

        return view('admin.daftar.staff.detail', compact([
            'staff',
        ]));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $staff = User::find($id);
        $gender = ['L', 'P'];

        return view('admin.daftar.staff.edit', compact([
            'staff',
            'gender',
        ]));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validatedData = $request->validate([
            'full_name' => 'required',
            'password' => 'required',
            'jk' => 'required',
            'bio' => 'nullable|max:50',
            'foto' => 'nullable|file|mimes:jpg,png,jpeg,gif,svg|max:2048',
        ]);

        if($request->file('foto')){
            if($request->oldImage){
                Storage::delete($request->oldImage);
            }
            $validatedData['foto'] = $request->file('foto')->store('foto-profile');
        }

        $staff = User::find($id);

        $staff->update($validatedData);
        return redirect('admin/daftar-staff')->with('success', 'Data Staff Berhasil Terupdate');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $deletedStaff = User::find($id);
        $deletedStaff->delete();

        return redirect('admin/daftar-staff')->with('success', 'Data Staff Berhasil Dihapus');
    }
}

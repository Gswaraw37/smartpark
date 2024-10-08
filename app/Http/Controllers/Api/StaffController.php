<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Http\Resources\StaffResource;
use Illuminate\Support\Facades\Validator;

class StaffController extends Controller
{
    public function index()
    {
        $staff = User::where('role', 'staff')->get();

        return new StaffResource(true, 'List Data Staff', $staff);
    }

    public function show($id)
    {
        $staff = User::where('role', 'staff')->find($id);

        if ($staff) {
            return new StaffResource(true, 'Detail Data Staff', $staff);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Data Staff Tidak Ditemukan',
            ]);
        }
    }

    public function store(Request $request)
    {
        $validatedData = Validator::make($request->all(),[
            'username' => 'required|unique:users',
            'full_name' => 'required',
            'email' => 'required|email|unique:users',
            'jk' => 'required',
            'bio' => 'nullable|max:50',
        ]);

        if ($validatedData->fails()) {
            return response()->json($validatedData->errors(), 422);
        }

        $staff = User::create([
            'username' => $request->username,
            'full_name' => $request->full_name,
            'email' => $request->email,
            'jk' => $request->jk,
            'bio' => $request->bio,
            'password' => Hash::make('12345678'),
            'role' => 'staff',
            'foto' => $request->foto,
        ]);

        return new StaffResource(true, 'Data Berhasil Ditambahkan', $staff);
    }

    public function update(Request $request, $id)
    {
        $validatedData = Validator::make($request->all(), [
            'full_name' => 'required',
            'password' => 'required',
            'jk' => 'required',
            'bio' => 'nullable|max:50',
        ]);

        if ($validatedData->fails()) {
            return response()->json($validatedData->errors(), 422);
        }

        $staff = User::whereId($id)->update([
            'full_name' => $request->full_name,
            'password' => $request->password,
            'jk' => $request->jk,
            'bio' => $request->bio,
            'foto' => $request->foto,
        ]);

        return new StaffResource(true, 'Data Berhasil Diubah', $staff);
    }

    public function destroy($id)
    {
        $staff = User::whereId($id)->first();
        $staff->delete();

        return new StaffResource(true, 'Data Berhasil Dihapus', $staff);
    }
}

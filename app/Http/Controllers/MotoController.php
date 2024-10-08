<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use App\Models\Approval;
use Illuminate\Http\Request;
use App\Models\ParkingSession;

class MotoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $vehicle = Vehicle::with('user', 'vehicleType')->get();
        $approval = Approval::all();
        $session = ParkingSession::all();

        return view('admin.daftar.kendaraan.motor', compact([
            'vehicle',
            'approval',
            'session',
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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}

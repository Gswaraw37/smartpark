<?php

namespace App\Exports;

use App\Models\Vehicle;
use App\Models\Approval;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class ExportSummary implements FromQuery, ShouldAutoSize, WithMapping, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    use Exportable;
    public function query()
    {
        $approval = Approval::with('vehicle')->orderBy('entry_time', 'ASC');
        $vehicle = Vehicle::with('user');

        return $approval;
    }

    public function map($approval): array {
        return [
            $approval->vehicle->user->username,
            $approval->vehicle->vehicleType->name,
            $approval->vehicle->licence_plate,
            $approval->entry_time,
            $approval->exit_time,
        ];
    }

    public function headings(): array
    {
        return [
            'Nama Pengunjung',
            'Jenis Kendaraan',
            'Plat Nomor',
            'Jam Masuk Parkir',
            'Jam Keluar Parkir',
        ];
    }
}

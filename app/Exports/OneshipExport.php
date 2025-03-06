<?php

namespace App\Exports;

use App\Models\Oneship;
use Maatwebsite\Excel\Concerns\FromCollection;

class OneshipExport implements FromCollection
{
    // /**
    // * @return \Illuminate\Support\Collection
    // */
    public function collection()
    {
        return Oneship::all();
    }
}

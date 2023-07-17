<?php

namespace App\Exports;

use App\Models\Auditrails;
use Maatwebsite\Excel\Concerns\FromCollection;

class AuditrailsExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Auditrails::all();
    }
}

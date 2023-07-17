<?php

namespace App\Http\Controllers\Auditrail;

use App\Exports\AuditrailsExport;
use App\Http\Controllers\Controller;
use App\Models\Auditrails;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Contracts\Database\Query\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class AuditrailController extends Controller
{
    public function getAuditrails(Request $request)
    {
        $request->input('limit') ? $limit = $request->input('limit') : $limit = 15;
        $subscribers = Auditrails::query()->when($request->input('search'), function ($query, $search) {
            $query->where('name_user', 'LIKE', '%' . $search . '%');
        })->when($request->input('date_from'), function ($query, $date_from) {
            $query->whereDate('created_at', '>=', $date_from);
        })->when($request->input('date_to'), function ($query, $date_to) {
            $query->whereDate('created_at', '<=', $date_to);
        })->orderBy('created_at', 'DESC')->paginate($limit);
        return response()->json($subscribers, 200);
    }

    public function csv()
    {
        return Excel::download(new AuditrailsExport, 'auditrails.xlsx', \Maatwebsite\Excel\Excel::XLSX);
    }
    
    public function pdf()
    {
        $data = Auditrails::all();
        $pdf = Pdf::loadView('auditrailsPdf', ['auditrails' => $data]);
        return $pdf->download();
    }
}

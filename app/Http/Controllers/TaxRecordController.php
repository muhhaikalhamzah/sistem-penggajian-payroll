<?php

namespace App\Http\Controllers;

use App\Models\TaxRecord;
use Illuminate\Http\Request;

class TaxRecordController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $taxRecords = TaxRecord::with('employee')->latest()->get();
        return view('tax-record.index', [
            'title' => 'Histori Pajak PPh21',
            'taxRecords' => $taxRecords
        ]);
    }

    public function show(TaxRecord $taxRecord)
    {
        return view('tax-record.show', [
            'title' => 'Detail Pajak PPh21',
            'tax' => $taxRecord
        ]);
    }
}

<?php

namespace App\Http\Controllers\V1\PDF;

use App\Http\Controllers\Controller;
use App\Models\TransportInvoice;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class TransportInvoicePdfController extends Controller
{
    public function __invoke(Request $request, TransportInvoice $transportInvoice)
    {
        if ($request->has('preview')) {
            return $transportInvoice->getPDFData();
        }

        $pdf = $transportInvoice->getPDFData();

        return response()->make($pdf->stream(), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="transport-invoice-'.$transportInvoice->unique_hash.'.pdf"',
        ]);
    }
}

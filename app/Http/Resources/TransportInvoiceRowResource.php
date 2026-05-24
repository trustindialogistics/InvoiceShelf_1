<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TransportInvoiceRowResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'consignment_no' => $this->consignment_no,
            'old_bill_date' => $this->old_bill_date,
            'invoice_no' => $this->invoice_no,
            'destination' => $this->destination,
            'vehicle_no' => $this->vehicle_no,
            'pkg' => $this->pkg,
            'charged_weight' => $this->charged_weight,
            'rate' => $this->rate,
            'other_charge' => $this->other_charge,
            'dd_charge' => $this->dd_charge,
            'amount' => $this->amount,
        ];
    }
}


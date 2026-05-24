<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TransportInvoiceResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'customer_id' => $this->customer_id,
            'lr_number' => $this->lr_number,
            'branch_code' => $this->branch_code,
            'invoice_date' => $this->invoice_date,
            'due_date' => $this->due_date,
            'unique_hash' => $this->unique_hash,
            'formatted_invoice_date' => $this->formattedInvoiceDate,
            'formatted_due_date' => $this->formattedDueDate,
            'pdf_url' => $this->transportInvoicePdfUrl,
            'customer' => $this->when($this->customer()->exists(), function () {
                return new CustomerResource($this->customer);
            }),
            'company' => $this->when($this->company()->exists(), function () {
                return new CompanyResource($this->company);
            }),
            'rows' => $this->when($this->rows()->exists(), function () {
                return TransportInvoiceRowResource::collection($this->rows);
            }),
        ];
    }
}


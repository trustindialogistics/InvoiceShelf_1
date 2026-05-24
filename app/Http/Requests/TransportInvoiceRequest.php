<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TransportInvoiceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'customer_id' => ['required', 'integer'],
            'lr_number' => ['required', 'string', 'max:255'],
            'branch_code' => ['nullable', 'string', 'max:255'],
            'invoice_date' => ['required', 'date'],
            'due_date' => ['nullable', 'date'],
            'rows' => ['required', 'array', 'min:1'],
            'rows.*.consignment_no' => ['nullable', 'string', 'max:255'],
            'rows.*.old_bill_date' => ['nullable', 'date'],
            'rows.*.invoice_no' => ['nullable', 'string', 'max:255'],
            'rows.*.destination' => ['nullable', 'string', 'max:255'],
            'rows.*.vehicle_no' => ['nullable', 'string', 'max:255'],
            'rows.*.pkg' => ['nullable', 'integer', 'min:0'],
            'rows.*.charged_weight' => ['nullable', 'numeric'],
            'rows.*.rate' => ['nullable', 'numeric'],
            'rows.*.other_charge' => ['nullable', 'numeric'],
            'rows.*.dd_charge' => ['nullable', 'numeric'],
            'rows.*.amount' => ['nullable', 'numeric'],
        ];
    }
}


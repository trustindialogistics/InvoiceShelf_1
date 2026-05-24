@php
    $invoiceCustomFields = $invoice->fields
        ->filter(function ($field) {
            return $field->customField && $field->customField->model_type === 'Invoice' && filled($field->defaultAnswer);
        })
        ->sortBy(function ($field) {
            return $field->customField->order;
        });
@endphp

@foreach ($invoiceCustomFields as $invoiceCustomField)
    <tr>
        <td class="attribute-label">{{ $invoiceCustomField->customField->label }}</td>
        <td class="attribute-value">
            &nbsp;{!! nl2br(e($invoiceCustomField->defaultAnswer)) !!}
        </td>
    </tr>
@endforeach

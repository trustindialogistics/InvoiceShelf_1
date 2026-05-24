<?php

namespace App\Http\Controllers\V1\Admin\TransportInvoice;

use App\Http\Controllers\Controller;
use App\Http\Requests\TransportInvoiceRequest;
use App\Http\Resources\TransportInvoiceResource;
use App\Models\Invoice;
use App\Models\TransportInvoice;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TransportInvoicesController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        // Reuse invoice abilities for now (keeps auth simple).
        $this->authorize('viewAny', Invoice::class);

        $limit = $request->input('limit', 10);

        $query = TransportInvoice::query()
            ->where('company_id', $request->header('company'))
            ->with(['customer', 'company'])
            ->latest();

        if ($request->filled('search')) {
            $query->where('lr_number', 'LIKE', '%'.$request->input('search').'%');
        }

        $invoices = $limit === 'all' ? $query->get() : $query->paginate($limit);

        return TransportInvoiceResource::collection($invoices)->response();
    }

    public function store(TransportInvoiceRequest $request): TransportInvoiceResource
    {
        $this->authorize('create', Invoice::class);

        $payload = $request->validated();
        $payload['company_id'] = (int) $request->header('company');

        $invoice = TransportInvoice::createTransportInvoice($payload);

        return new TransportInvoiceResource($invoice);
    }

    public function show(Request $request, TransportInvoice $transportInvoice): TransportInvoiceResource
    {
        $this->authorize('view', Invoice::class);

        abort_unless((int) $transportInvoice->company_id === (int) $request->header('company'), 404);

        return new TransportInvoiceResource($transportInvoice->load(['rows', 'customer', 'company']));
    }

    public function update(TransportInvoiceRequest $request, TransportInvoice $transportInvoice): TransportInvoiceResource
    {
        $this->authorize('update', Invoice::class);

        abort_unless((int) $transportInvoice->company_id === (int) $request->header('company'), 404);

        $payload = $request->validated();

        return new TransportInvoiceResource($transportInvoice->updateTransportInvoice($payload));
    }

    public function destroy(Request $request, TransportInvoice $transportInvoice): JsonResponse
    {
        $this->authorize('delete', Invoice::class);

        abort_unless((int) $transportInvoice->company_id === (int) $request->header('company'), 404);

        $transportInvoice->delete();

        return response()->json(['success' => true]);
    }
}


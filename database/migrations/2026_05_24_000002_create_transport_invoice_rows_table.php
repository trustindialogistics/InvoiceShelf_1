<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transport_invoice_rows', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('transport_invoice_id');

            $table->string('consignment_no')->nullable();
            $table->date('old_bill_date')->nullable();
            $table->string('invoice_no')->nullable();
            $table->string('destination')->nullable();
            $table->string('vehicle_no')->nullable();
            $table->unsignedInteger('pkg')->nullable();
            $table->decimal('charged_weight', 12, 3)->nullable();
            $table->decimal('rate', 12, 2)->nullable();
            $table->decimal('other_charge', 12, 2)->nullable();
            $table->decimal('dd_charge', 12, 2)->nullable();
            $table->decimal('amount', 14, 2)->nullable();

            $table->timestamps();

            $table
                ->foreign('transport_invoice_id')
                ->references('id')
                ->on('transport_invoices')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transport_invoice_rows');
    }
};


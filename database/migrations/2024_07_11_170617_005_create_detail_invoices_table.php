<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detail_invoices', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('invoice_purchase_id')->unsigned();
            $table->bigInteger('detail_request_id')->unsigned();
            $table->decimal('quantity_product');
            $table->decimal('quantity_invoice');
            $table->bigInteger('unit_id')->unsigned();
            $table->bigInteger('subtotal_price');
            $table->tinyInteger('status');
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();

            $table
                ->foreign('invoice_purchase_id')
                ->references('id')
                ->on('invoice_purchases')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table
                ->foreign('detail_request_id')
                ->references('id')
                ->on('detail_requests')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table
                ->foreign('unit_id')
                ->references('id')
                ->on('units')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('detail_invoices');
    }
};

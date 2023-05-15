<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->createOrdersTable();
        $this->createOrderItemsTable();
    }

    private function createOrdersTable()
    {

        Schema::create('orders', function (Blueprint $table) {

            $table->increments('id');
            $table->unsignedInteger('user_id');
            $table->enum('payment_type', ['subscribe', 'order']);
            $table->tinyText('payment_period');
             $table->enum('payment_status', ['payed', 'no_payed'])->nullable()->default('no_payed');
            $table->enum('status', [
                'new',
                'in_progress',
                'error',
                'completed',
                'renewal',
                'rejected',

            ])->nullable()->default('new');

            $table->dateTime('payed_at')->nullable();

            $table->timestamps();
            $table->softDeletes();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();
            $table->ipAddress('created_ip')->nullable();
            $table->ipAddress('updated_ip')->nullable();
            $table->ipAddress('deleted_ip')->nullable();
        });
    }

    private function createOrderItemsTable() {

        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('order_id');
            $table->unsignedInteger('service_item_id');
            $table->decimal('price', 24, 8)->default(0)->nullable();
            $table->decimal('price_for_sale', 24, 8)->default(0)->nullable();
            $table->decimal('count', 24, 8)->default(0)->nullable();

            $table->foreign('order_id')->references('id')->on('orders');
            $table->foreign('service_item_id')->references('id')->on('service_items');
            $table->unique(['order_id', 'service_item_id']);

            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_items');
        Schema::dropIfExists('orders');
    }
};

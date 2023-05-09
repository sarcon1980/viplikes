<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $this->addService();
        $this->addItemService();
        //$this->itemPrices();
        $this->serviceOptions();
        $this->servicePeriods();
    }

    public function addService()
    {
        Schema::create('services', function (Blueprint $table) {
            $table->increments('id');

            $table->string('name', 100);
            $table->integer('parent_id')->default(0)->unsigned()->index();
            $table->string('url')->index()->nullable();
            $table->string('alias', 100)->unique();
            $table->tinyInteger('position')->default(0);
            $table->enum('type', ['product', 'package','default'])->nullable();

            $table->boolean('is_active')->default(true);
            $table->boolean('is_bestseller')->default(false);
            $table->boolean('is_sale')->default(false);
            $table->boolean('is_popular')->default(false);
            $table->boolean('is_recommendation')->default(false);

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

    public function addItemService()
    {
        Schema::create('service_items', function (Blueprint $table) {

            $table->increments('id');
            $table->boolean('is_active')->default(true);
            $table->string('name', 100)->nullable();
            $table->unsignedInteger('service_id');
            $table->tinyInteger('position');
            $table->string('type', 100)->nullable();

            $table->smallInteger('count')->default(1)->nullable();
            $table->float('price')->default(0);
            $table->float('price_for_sale')->default(0);
            $table->float('discount')->default(0);

            $table->timestamps();
            $table->softDeletes();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();
            $table->ipAddress('created_ip')->nullable();
            $table->ipAddress('updated_ip')->nullable();
            $table->ipAddress('deleted_ip')->nullable();

            $table->foreign('service_id')->references('id')->on('services');
        });

    }

//    public function itemPrices()
//    {
//        Schema::create('item_prices', function (Blueprint $table) {
//            $table->increments('id');
//            $table->unsignedInteger('service_item_id');
//            $table->smallInteger('count')->default(1);
//            $table->float('price')->default(0);
//            $table->float('price_for_sale')->default(0);
//            $table->float('discount')->default(0);
//
//            $table->timestamps();
//            $table->softDeletes();
//            $table->unsignedBigInteger('created_by')->nullable();
//            $table->unsignedBigInteger('updated_by')->nullable();
//            $table->unsignedBigInteger('deleted_by')->nullable();
//            $table->ipAddress('created_ip')->nullable();
//            $table->ipAddress('updated_ip')->nullable();
//            $table->ipAddress('deleted_ip')->nullable();
//
//            $table->foreign('service_item_id')->references('id')->on('service_items');
//        });
//    }

    public function serviceOptions()
    {

        Schema::create('service_options', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('service_id');
            $table->string('title');
            $table->string('accounts');
            $table->string('types');

            $table->timestamps();
            $table->softDeletes();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();
            $table->ipAddress('created_ip')->nullable();
            $table->ipAddress('updated_ip')->nullable();
            $table->ipAddress('deleted_ip')->nullable();

            $table->foreign('service_id')->references('id')->on('services');

        });
    }

    public function servicePeriods()
    {

        Schema::create('service_periods', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('alias');
        });

        DB::table('service_periods')->insert([
            ['alias' => 'day', 'name' => 'Daily'],
            ['alias' => 'week', 'name' => 'Weekly'],
            ['alias' => 'mount', 'name' => 'Monthly'],
            ['alias' => 'year', 'name' => 'Yearly']
        ]);
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('service_periods');
        Schema::dropIfExists('service_options');
        Schema::dropIfExists('item_prices');
        Schema::dropIfExists('service_items');
        Schema::dropIfExists('services');
    }
};

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOfferingOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('offering_orders', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->string('invoice_number');
            $table->unsignedInteger('therapist_id');
            $table->unsignedInteger('customer_id');
            $table->enum('accepted',[true, false])->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('offering_orders');
    }
}

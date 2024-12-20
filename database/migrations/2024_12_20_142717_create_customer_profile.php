<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('customer_profile', function (Blueprint $table) {
            $table->id();
            
            $table->string('cus_name',50);
            $table->string('cus_add',50);
            $table->string('cus_city',50);
            $table->string('cus_state', 50);
            $table->string('cus_postcode',50);
            $table->string('cus_country',50);
            $table->string('cus_phone',50);
            $table->string('cus_fax',50);

            $table->string('ship_name',100);
            $table->string('ship_add',50);
            $table->string('ship_city',50);
            $table->string('ship_postcode',50);
            $table->string('ship_country',50);
            $table->string('ship_phone',50);

            $table->unsignedBigInteger('user_id')->unique();
            $table->foreign('user_id')->references('id')->on('users')->restrictOnDelete()->cascadeOnUpdate();

            $table->timestamp('created-at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customer_profile');
    }
};

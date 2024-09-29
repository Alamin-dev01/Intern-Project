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
        Schema::create('discount_coupons', function (Blueprint $table) {
            $table->id();

            //The discount coupon code date:30/09/2024 time: a good night with new module coding (discount)Md. Al-Amin
            $table->string('code'); 

            // The human readable discount coupon code name
            $table->string('name')->nullable();

            //The description of the coupon - not necessary
            $table->text('description')->nullable(); 

            //The max uses this discount coupon has 
            $table->integer('max_uses')->nullable();

            // Howe many time a user can use this coupon.
            $table->integer('max_uses_user')->nullable();

            //Whether or not the coupon is a percentage or a fixed price
            $table->enum('type',['percent','fixed'])->default('fixed');

            //The amount to discount based on type
            $table->double('discount_amount', 100,2);

            //The amount to discount qual or smaller then subtotal
            $table->double('min_amount', 100,2)->nullable();

            //Active(1), inactive(0)
            $table->integer('status')->default(1);

            //When the coupon begins
            $table->timestamp('starts_at')->nullable();

            //When the coupon ends 
            $table->timestamp('expires_at')->nullable();



            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('discount_coupons');
    }
};

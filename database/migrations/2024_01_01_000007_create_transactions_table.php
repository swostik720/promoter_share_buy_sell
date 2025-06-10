<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sell_application_id')->constrained('sell_applications');
            $table->foreignId('buy_application_id')->constrained('buy_applications');
            $table->foreignId('seller_id')->constrained('shareholders');
            $table->integer('share_quantity');
            $table->decimal('price_per_share', 10, 2);
            $table->decimal('total_amount', 15, 2);
            $table->date('transaction_date');
            $table->string('transaction_reference')->unique();
            $table->enum('status', ['pending', 'completed', 'failed'])->default('pending');
            $table->json('regulatory_notifications')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('transactions');
    }
};

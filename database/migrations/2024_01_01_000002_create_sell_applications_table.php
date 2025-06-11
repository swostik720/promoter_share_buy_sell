<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('sell_applications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('seller_id')->constrained('shareholders');
            $table->enum('seller_type', ['individual', 'institutional'])->nullable();
            $table->integer('share_quantity_to_sell');
            $table->decimal('proposed_price_per_share', 10, 2)->nullable();
            $table->date('application_date');
            $table->string('demat_account')->nullable();
            $table->string('boid')->nullable(); // Auto-filled from shareholder
            $table->enum('status', ['pending', 'board_approved', 'board_rejected', 'notice_published', 'completed', 'cancelled'])->default('pending');
            $table->text('reason')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('sell_applications');
    }
};

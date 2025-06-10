<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('buy_applications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sell_application_id')->constrained('sell_applications');
            $table->string('buyer_name');
            $table->enum('buyer_type', ['individual', 'institutional']);
            $table->enum('buyer_category', ['existing_promoter', 'public']);
            $table->integer('share_quantity_to_buy');
            $table->decimal('offered_price_per_share', 10, 2);
            $table->date('application_date');
            $table->enum('status', ['pending', 'approved', 'rejected', 'completed'])->default('pending');
            $table->string('citizenship_number')->nullable();
            $table->string('pan_number')->nullable();
            $table->string('demat_account')->nullable();
            $table->json('contact_details')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('buy_applications');
    }
};

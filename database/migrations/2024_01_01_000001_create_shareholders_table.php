<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('shareholders', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('type', ['individual', 'institutional']);
            $table->enum('category', ['promoter', 'public'])->default('promoter');
            $table->integer('share_quantity');
            $table->string('citizenship_number')->nullable();
            $table->string('pan_number')->nullable();
            $table->string('demat_account')->nullable();
            $table->json('contact_details')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('shareholders');
    }
};

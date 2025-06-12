<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('notice_publications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sell_application_id')->constrained('sell_applications');
            $table->date('publication_date');
            $table->string('newspaper_name');
            $table->string('notice_reference')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('notice_publications');
    }
};

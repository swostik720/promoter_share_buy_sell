<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('board_decisions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sell_application_id')->constrained('sell_applications');
            $table->date('decision_date');
            $table->enum('decision', ['approved', 'rejected']);
            $table->text('decision_remarks')->nullable();
            $table->json('board_members_present')->nullable();
            $table->string('meeting_minute_reference')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('board_decisions');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->morphs('documentable'); // For polymorphic relationship
            $table->enum('document_type', [
                'application',
                'citizenship',
                'tax_clearance',
                'cia_report',
                'moa_aoa',
                'decision_minute',
                'income_source',
                'combine_application',
                'police_report',
                'self_declaration',
                'sebbon_notification',
                'nepse_notification',
                'other'
            ]);
            $table->string('file_name');
            $table->string('file_path');
            $table->string('file_type');
            $table->integer('file_size');
            $table->date('upload_date');
            $table->boolean('is_verified')->default(false);
            $table->text('remarks')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('documents');
    }
};

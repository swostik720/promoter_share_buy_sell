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
                // Sell Application Documents
                'sell_application',
                'seller_citizenship',
                'seller_tax_clearance',
                'seller_cia_report',
                'seller_moa_aoa',
                'seller_decision_minute',
                'seller_others',
                
                // Buy Application Documents
                'buy_application',
                'buyer_citizenship',
                'buyer_cia_report',
                'buyer_tax_clearance',
                'buyer_income_source',
                'buyer_moa_aoa',
                'buyer_decision_minute',
                'buyer_others',
                'combine_application',
                'police_report',
                'self_declaration',
                
                // Regulatory Documents
                'sebbon_notification',
                'nepse_notification',
                'nia_notification',
                
                // Board Documents
                'board_decision_minute',
                'notice_publication',
                
                // General
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

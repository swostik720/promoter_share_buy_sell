<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSellApplicationDocumentsTable extends Migration
{
    public function up()
    {
        Schema::create('sell_application_documents', function (Blueprint $table) {
            $table->id('document_id');
            $table->foreignId('application_id')->constrained('share_sell_applications', 'application_id')->onDelete('cascade');
            $table->enum('document_type', ['Application', 'Citizenship', 'Tax_Clearance', 'CIA_Report', 'MOA_AOA', 'Decision_Minute', 'Others']);
            $table->string('document_name');
            $table->string('document_path', 500);
            $table->string('uploaded_by', 100)->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('sell_application_documents');
    }
}

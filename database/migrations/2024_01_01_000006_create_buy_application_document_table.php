<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBuyApplicationDocumentsTable extends Migration
{
    public function up()
    {
        Schema::create('buy_application_documents', function (Blueprint $table) {
            $table->id('document_id');
            $table->foreignId('buy_application_id')->constrained('buy_applications', 'buy_application_id')->onDelete('cascade');
            $table->enum('document_type', ['Buy_Application', 'Citizenship', 'CIA_Report', 'Tax_Clearance', 'Income_Source', 'MOA_AOA', 'Decision_Minute', 'Combine_Application', 'Police_Report', 'Self_Declaration', 'Others']);
            $table->string('document_name');
            $table->string('document_path', 500);
            $table->string('uploaded_by', 100)->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('buy_application_documents');
    }
}

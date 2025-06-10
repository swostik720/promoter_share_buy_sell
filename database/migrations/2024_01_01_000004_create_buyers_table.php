<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBuyersTable extends Migration
{
    public function up()
    {
        Schema::create('buyers', function (Blueprint $table) {
            $table->id('buyer_id');
            $table->string('buyer_name');
            $table->enum('buyer_type', ['Individual', 'Institutional']);
            $table->enum('buyer_category', ['Existing_Promoter', 'Public']);
            $table->string('demat_account_number', 50)->nullable();
            $table->string('contact_email', 100)->nullable();
            $table->string('contact_phone', 20)->nullable();
            $table->text('address')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('buyers');
    }
}

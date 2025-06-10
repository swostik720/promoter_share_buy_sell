<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePromoterShareholdersTable extends Migration
{
    public function up()
    {
        Schema::create('promoter_shareholders', function (Blueprint $table) {
            $table->id('shareholder_id');
            $table->string('shareholder_name');
            $table->enum('shareholder_type', ['Individual', 'Institutional']);
            $table->decimal('share_quantity', 15, 2);
            $table->string('demat_account_number', 50)->nullable();
            $table->string('contact_email', 100)->nullable();
            $table->string('contact_phone', 20)->nullable();
            $table->text('address')->nullable();
            $table->enum('status', ['Active', 'Inactive'])->default('Active');
            $table->timestamps();

            $table->index('shareholder_name');
        });
    }

    public function down()
    {
        Schema::dropIfExists('promoter_shareholders');
    }
}

<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShareSellApplicationsTable extends Migration
{
    public function up()
    {
        Schema::create('share_sell_applications', function (Blueprint $table) {
            $table->id('application_id');
            $table->foreignId('seller_id')->constrained('promoter_shareholders', 'shareholder_id');
            $table->date('application_date');
            $table->decimal('share_quantity_to_sell', 15, 2);
            $table->enum('application_status', ['Submitted', 'Under Review', 'BOD Approved', 'BOD Rejected', 'Completed'])->default('Submitted');
            $table->date('bod_decision_date')->nullable();
            $table->enum('bod_decision', ['Approved', 'Rejected', 'Pending'])->default('Pending');
            $table->text('bod_remarks')->nullable();
            $table->date('notice_publication_date')->nullable();
            $table->string('newspaper_name', 100)->nullable();
            $table->timestamps();

            $table->index('application_date');
        });
    }

    public function down()
    {
        Schema::dropIfExists('share_sell_applications');
    }
}

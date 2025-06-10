<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBuyApplicationsTable extends Migration
{
    public function up()
    {
        Schema::create('buy_applications', function (Blueprint $table) {
            $table->id('buy_application_id');
            $table->foreignId('sell_application_id')->constrained('share_sell_applications', 'application_id');
            $table->foreignId('buyer_id')->constrained('buyers', 'buyer_id');
            $table->date('application_date');
            $table->decimal('requested_share_quantity', 15, 2);
            $table->enum('application_status', ['Submitted', 'Under Review', 'Approved', 'Rejected'])->default('Submitted');
            $table->boolean('is_combine_application')->default(false);
            $table->timestamps();

            $table->index(['sell_application_id', 'buyer_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('buy_applications');
    }
}

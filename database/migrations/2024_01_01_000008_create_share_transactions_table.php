<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShareTransactionsTable extends Migration
{
    public function up()
    {
        Schema::create('share_transactions', function (Blueprint $table) {
            $table->id('transaction_id');
            $table->foreignId('sell_application_id')->constrained('share_sell_applications', 'application_id');
            $table->foreignId('buy_application_id')->constrained('buy_applications', 'buy_application_id');
            $table->foreignId('seller_id')->constrained('promoter_shareholders', 'shareholder_id');
            $table->foreignId('buyer_id')->constrained('buyers', 'buyer_id');
            $table->date('transaction_date');
            $table->decimal('share_quantity', 15, 2);
            $table->decimal('price_per_share', 10, 2)->nullable();
            $table->decimal('total_amount', 18, 2)->nullable();
            $table->enum('transaction_status', ['Pending', 'Completed', 'Failed'])->default('Pending');
            $table->date('settlement_date')->nullable();
            $table->timestamps();

            $table->index('transaction_date');
        });
    }

    public function down()
    {
        Schema::dropIfExists('share_transactions');
    }
}

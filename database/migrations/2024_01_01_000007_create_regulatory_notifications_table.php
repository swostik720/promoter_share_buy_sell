<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRegulatoryNotificationsTable extends Migration
{
    public function up()
    {
        Schema::create('regulatory_notifications', function (Blueprint $table) {
            $table->id('notification_id');
            $table->foreignId('sell_application_id')->constrained('share_sell_applications', 'application_id');
            $table->enum('regulatory_body', ['SEBBON', 'NEPSE', 'NIA']);
            $table->enum('notification_type', ['Sell_Notification', 'Transaction_Notification']);
            $table->date('notification_date');
            $table->enum('notification_status', ['Sent', 'Acknowledged', 'Pending'])->default('Pending');
            $table->string('reference_number', 100)->nullable();
            $table->text('remarks')->nullable();
            $table->timestamps();

            $table->index('notification_date');
        });
    }

    public function down()
    {
        Schema::dropIfExists('regulatory_notifications');
    }
}

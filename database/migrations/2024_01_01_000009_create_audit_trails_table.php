<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAuditTrailsTable extends Migration
{
    public function up()
    {
        Schema::create('audit_trails', function (Blueprint $table) {
            $table->id('audit_id');
            $table->string('table_name', 50);
            $table->unsignedBigInteger('record_id');
            $table->enum('action', ['INSERT', 'UPDATE', 'DELETE']);
            $table->json('old_values')->nullable();
            $table->json('new_values')->nullable();
            $table->string('user_id', 100)->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('audit_trails');
    }
}

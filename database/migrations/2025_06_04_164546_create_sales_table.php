<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
{
    Schema::create('sales', function (Blueprint $table) {
        $table->engine = 'InnoDB';
        $table->id();
        $table->foreignId('client_id')->constrained()->onDelete('cascade');
        $table->date('data');
        $table->decimal('total', 10, 2)->default(0);
        $table->enum('status', ['pago', 'fiado'])->default('pago');
        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales');
    }
};

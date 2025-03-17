<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('vnpost', function (Blueprint $table) {
            $table->string('e1_code')->primary();
            $table->date('release_date')->nullable();
            $table->integer('chargeable_volumn')->nullable();
            $table->decimal('main_charge', 10, 2)->nullable();
            $table->string('receiver')->nullable();
            $table->text('recipient_address')->nullable();
            $table->string('phone_number')->nullable();
            $table->string('reference_number')->nullable();
            $table->string('file_name')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vnpost');
    }
};

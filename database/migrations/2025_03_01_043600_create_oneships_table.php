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
        Schema::create('oneships', function (Blueprint $table) {
            $table->string('e1_code') ->unique();
            $table -> string('release_date')->nullable();
            $table -> string('chargeable_volumn')->nullable();
            $table -> string('main_charge')->nullable();
            $table -> string('receiver')->nullable();
            $table -> string('recipient_address')->nullable();
            $table -> string('phone_number')->nullable();
            $table -> string('reference_number')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('oneships');
    }
};

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
            $table-> date('release_date')->nullable();
            $table -> string('chargeable_volumn')->nullable();
            $table -> string('main_charge')->nullable();
            $table -> string('receiver')->nullable();
            $table -> string('recipient_address')->nullable();
            $table -> string('phone_number')->nullable();
            $table -> string('reference_number')->nullable();
            $table -> string('file_name')->nullable();
            $table -> unsignedBigInteger('carrier_id')->nullable();
            $table -> foreign('carrier_id')->references('id')->on('carriers')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vnpost', function (Blueprint $table){
            $table -> dropForeign('[carrier_id]');
            $table->dropColumn('carrier_id');
        });
        Schema::dropIfExists('vnpost');
    }
};

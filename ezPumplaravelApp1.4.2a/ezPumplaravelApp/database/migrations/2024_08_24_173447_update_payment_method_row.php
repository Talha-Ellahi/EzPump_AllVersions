<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        //
        DB::table('paymentmethod')
        ->where('id', 2)
        ->update(['logo_profile' => 'bank_image.png']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};

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
        if (!Schema::hasTable('settings')) {

            Schema::create('settings', function (Blueprint $table) {
                $table->id();
                $table->string('key');
                $table->string('value');
                $table->string('description');
                $table->string('type');
                $table->timestamps();
            });
            DB::table('settings')->insert([
                [
                    'key' => 'name',
                    'value' => 'My Pump',
                    'description' => 'The name of the website',
                    'type' => 'text',
                    'created_at' => now(),
                    'updated_at' => now(),
                ], [
                    'key' => 'logo',
                    'value' => '0',
                    'description' => 'Pump Logo',
                    'type' => 'file',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};

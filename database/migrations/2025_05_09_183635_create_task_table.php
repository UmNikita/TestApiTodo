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
        Schema::create('status', function (Blueprint $table) {
            $table->id();
            $table->string('name', 45);
            $table->timestamps();
        });
         DB::table('status')->insert([
            ['name' => 'Выполнена'],
            ['name' => 'Не выполнена'],
        ]);
        Schema::create('task', function (Blueprint $table) {
            $table->id();
            $table->string('name', 45);
            $table->string('description', 45);
            $table->foreignId('status_id')->constrained('status');
            $table->timestamps();
            $table->primary('id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('task');
        Schema::dropIfExists('status');
    }
};

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
        Schema::create('permissions', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 50)->unique();
            $table->string('key', 50)->unique();
            $table->enum('method', ['get', 'post', 'put', 'delete'])->nullable();
            $table->enum('type', ['Form', 'Default', 'Confirm'])->nullable();
            $table->smallInteger('position')->comment('0 => top-left, 1=> top-right, 2 => line');
            $table->timestamps();

            $table->index(['created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('permissions');
    }
};

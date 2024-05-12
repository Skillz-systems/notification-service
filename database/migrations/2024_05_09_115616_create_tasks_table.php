<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->bigInteger('id')->primary();
            $table->unsignedBigInteger('owner_id');
            $table->timestamp('due_at')->nullable();
            $table->string('title');
            $table->enum('for', ['staff', 'customer', 'supplier', 'other'])->defaultValue('staff');
            // $table->string('owner_type');
            $table->enum('status', ['visible', 'hidden', 'completed', 'staled'])->default('visible');
            $table->string('content')->nullable();
            $table->string('owner_email')->nullable();
            $table->string('url')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
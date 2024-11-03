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
        Schema::create('tasks', function (Blueprint $table) {
            $table->id(); // Auto increment and primary key
            $table->foreignId('status_id')->constrained('statuses')->onDelete('cascade'); 
            $table->string('percentage')->default('0%');
            $table->string('subject');
            $table->foreignId('system_id')->constrained('systems')->onDelete('cascade'); 
            $table->foreignId('priority_id')->constrained('statuses')->onDelete('cascade');
            $table->text('definition');
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

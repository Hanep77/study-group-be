<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('checklists', function (Blueprint $table) {
            $table->id();
            $table->foreignId('task_id')->constrained('tasks')->onDelete('cascade');
            $table->string('item');
            $table->boolean('completed')->default(false);
            $table->timestamps();
            
            $table->index('task_id');
            $table->index('completed');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('checklists');
    }
};
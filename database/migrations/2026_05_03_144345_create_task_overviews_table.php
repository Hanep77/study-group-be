<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('task_overviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('group_id')->constrained('groups')->onDelete('cascade');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null');
            $table->text('content');
            $table->text('attachment_url')->nullable();
            $table->timestamps();
            
            $table->index('group_id');
            $table->index('updated_by');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('task_overviews');
    }
};
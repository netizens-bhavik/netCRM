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
            $table->uuid('id')->primary();
            $table->string('name');
            $table->uuid('project_id')->nullable();
            $table->foreign('project_id')->references('id')->on('projects')->onDelete('cascade');
            $table->date('start_date')->nullable();
            $table->date('due_date')->nullable();
            $table->string('description')->nullable();
            $table->string('priority')->nullable();
            $table->string('status')->nullable();
            $table->string('voice_memo')->nullable();
            $table->uuid('manage_by')->nullable();
            $table->foreign('manage_by')->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();
            // $table->softDeletes();
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

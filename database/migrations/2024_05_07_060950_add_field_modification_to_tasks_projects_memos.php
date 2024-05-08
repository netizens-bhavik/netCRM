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
        Schema::table('tasks', function (Blueprint $table) {
            $table->uuid('manage_by')->nullable()->change();
            $table->uuid('project_id')->nullable()->change();
            $table->renameColumn('manage_by', 'created_by');
            $table->uuid('assigned_to')->nullable()->after('manage_by');
        });

        Schema::table('projects', function (Blueprint $table) {
            $table->uuid('client_id')->nullable()->change();
        });

        Schema::table('memos', function (Blueprint $table) {
            $table->string('status')->nullable()->after('description');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->uuid('manage_by')->change();
            $table->uuid('project_id')->change();
            $table->renameColumn('created_by', 'manage_by');
        });

        Schema::table('projects', function (Blueprint $table) {
            $table->uuid('client_id')->change();
        });
    }
};

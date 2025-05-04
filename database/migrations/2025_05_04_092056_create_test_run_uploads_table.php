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
        Schema::create('test_run_uploads', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('test_run_id');
            $table->unsignedBigInteger('test_case_id');
            $table->string('file_name');
            $table->string('file_path');
            $table->timestamps();

            $table->foreign('test_run_id')->references('id')->on('test_runs')->onDelete('cascade');
            $table->foreign('test_case_id')->references('id')->on('test_cases')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('test_run_uploads');
    }
};

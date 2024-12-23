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
        Schema::create('enrollments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('fullname');
            $table->string('phone');
            $table->string('email');
            $table->unsignedBigInteger('formation_id');
            $table->foreign('formation_id')->references('id')->on('formations')->onDelete('cascade');
            $table->decimal('price', 10, 2);
            $table->unsignedBigInteger('language_id');
            $table->foreign('language_id')->references('id')->on('language_mediums')->onDelete('cascade');
            $table->string('promo_code')->nullable();
            $table->text('comment')->nullable();
            $table->enum('status', ['draft', 'validated'])->default('draft');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('enrollments');
    }
};

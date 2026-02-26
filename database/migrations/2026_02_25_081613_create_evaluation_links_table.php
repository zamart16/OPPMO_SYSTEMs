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
        Schema::create('evaluation_links', function (Blueprint $table) {
            $table->id();

            // Link to evaluation
            $table->foreignId('evaluation_id')
                  ->constrained('evaluations')
                  ->onDelete('cascade');

            // Secure unique token used in URL
            $table->string('token')->unique();

            // Who is reviewing (Head, Manager, Director, etc.)
            $table->string('reviewer_role');

            // Optional: restrict access to specific email
            $table->string('reviewer_email')->nullable();

            // Optional: expiration control
            $table->timestamp('expires_at')->nullable();

            // Track usage
            $table->timestamp('used_at')->nullable();

            // Prevent multiple submissions
            $table->boolean('is_completed')->default(false);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evaluation_links');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('loans', function (Blueprint $table) {
            $table->id();
            $table->string('loan_code')->unique();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('book_id')->constrained()->cascadeOnDelete();
            $table->date('loan_date');
            $table->date('due_date');
            $table->date('return_date')->nullable();
            $table->enum('status', ['pending', 'approved', 'borrowed', 'returned', 'rejected', 'overdue'])->default('pending');
            $table->decimal('fine_amount', 10, 2)->default(0);
            $table->boolean('fine_paid')->default(false);
            $table->text('notes')->nullable();
            $table->foreignId('officer_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('loans');
    }
};


<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Add reading progress & format columns to reading_histories if not exists
        Schema::table('reading_histories', function (Blueprint $table) {
            $table->unsignedInteger('total_pages')->default(1)->after('last_page');
            $table->boolean('is_completed')->default(false)->after('total_pages');
        });

        // Add payment reference to loans for simulated payments
        Schema::table('loans', function (Blueprint $table) {
            $table->string('payment_method')->nullable()->after('fine_paid'); // e.g. 'qris_simulasi', 'tunai'
            $table->string('payment_reference')->nullable()->after('payment_method');
            $table->timestamp('fine_paid_at')->nullable()->after('payment_reference');
        });

        // Reading Challenges Table
        Schema::create('reading_challenges', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->unsignedInteger('year');
            $table->unsignedInteger('target_books')->default(12);
            $table->timestamps();

            $table->unique(['user_id', 'year']);
        });

        // Custom Shelves Table (e.g. Ingin Dibaca, Sedang Dibaca, Selesai Dibaca)
        Schema::create('user_shelves', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('book_id')->constrained()->cascadeOnDelete();
            $table->enum('shelf_type', ['want_to_read', 'currently_reading', 'already_read'])->default('want_to_read');
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->unique(['user_id', 'book_id', 'shelf_type']);
        });

        // Notification logs (e.g. WhatsApp, Email)
        Schema::create('circulation_notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('loan_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('channel'); // 'whatsapp', 'email'
            $table->string('type'); // 'due_reminder', 'overdue_alert', 'loan_approved'
            $table->string('recipient');
            $table->text('message');
            $table->enum('status', ['sent', 'failed'])->default('sent');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('circulation_notifications');
        Schema::dropIfExists('user_shelves');
        Schema::dropIfExists('reading_challenges');

        Schema::table('loans', function (Blueprint $table) {
            $table->dropColumn(['payment_method', 'payment_reference', 'fine_paid_at']);
        });

        Schema::table('reading_histories', function (Blueprint $table) {
            $table->dropColumn(['total_pages', 'is_completed']);
        });
    }
};

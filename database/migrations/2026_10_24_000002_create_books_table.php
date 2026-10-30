<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('author');
            $table->string('publisher');
            $table->unsignedSmallInteger('publication_year');
            $table->string('isbn')->unique();
            $table->unsignedInteger('pages')->nullable();
            $table->string('language')->default('Indonesia');
            $table->string('shelf_location')->default('Rak Umum');
            $table->unsignedInteger('stock_total')->default(5);
            $table->unsignedInteger('stock_available')->default(5);
            $table->text('description');
            $table->string('cover_image')->nullable();
            $table->boolean('has_ebook')->default(false);
            $table->string('ebook_file')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->unsignedBigInteger('views_count')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('books');
    }
};


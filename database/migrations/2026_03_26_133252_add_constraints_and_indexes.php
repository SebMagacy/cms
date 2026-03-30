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
        Schema::table('cms_posts', function (Blueprint $table) {
            $table->foreign('user_id')->on('users')->onDelete('cascade');

            $table->foreign('category_id')
                ->references('id')
                ->on('cms_categories') // TUTAJ: dodaj cms_
                ->onDelete('restrict');

            $table->index('status');
            $table->index('published_at');
            $table->index(['status', 'published_at']);
        });

        Schema::table('cms_categories', function (Blueprint $table) {
            $table->foreign('parent_id')
                ->references('id')
                ->on('cms_categories') // TUTAJ: dodaj cms_
                ->nullOnDelete();

            $table->index('parent_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};

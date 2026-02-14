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
        Schema::table('expenses', function (Blueprint $table) {
            $table->boolean('is_recurring')->default(false);
            $table->string('recurring_type')->nullable(); // e.g., 'monthly', 'weekly'
            $table->integer('recurring_day')->nullable(); // day of month or week
            $table->date('recurring_end_date')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('expenses', function (Blueprint $table) {
            $table->dropColumn('is_recurring');
            $table->dropColumn('recurring_type');
            $table->dropColumn('recurring_day');
            $table->dropColumn('recurring_end_date');
        });
    }
};

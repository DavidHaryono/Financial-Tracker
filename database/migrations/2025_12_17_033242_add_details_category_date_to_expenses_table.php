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
            $table->text('details')->nullable()->after('title');
            $table->enum('category', ['transportation', 'food', 'home_utilities', 'entertainment'])->default('food')->after('details');
            $table->timestamp('expense_date')->nullable()->after('receipt');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('expenses', function (Blueprint $table) {
            $table->dropColumn(['details', 'category', 'expense_date']);
        });
    }
};
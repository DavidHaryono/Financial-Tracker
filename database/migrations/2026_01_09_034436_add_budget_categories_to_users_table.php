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
        Schema::table('users', function (Blueprint $table) {
            $table->integer('budget_transportation')->default(20)->after('savings_percentage');
            $table->integer('budget_food')->default(35)->after('budget_transportation');
            $table->integer('budget_home_utilities')->default(30)->after('budget_food');
            $table->integer('budget_entertainment')->default(15)->after('budget_home_utilities');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'budget_transportation',
                'budget_food',
                'budget_home_utilities',
                'budget_entertainment'
            ]);
        });
    }
};
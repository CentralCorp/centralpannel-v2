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
        Schema::table('options_general', function (Blueprint $table) {
            $table->integer('active_theme_id')->nullable()->after('market_api_key');
            $table->string('active_theme_version', 50)->nullable()->after('active_theme_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('options_general', function (Blueprint $table) {
            $table->dropColumn(['active_theme_id', 'active_theme_version']);
        });
    }
};

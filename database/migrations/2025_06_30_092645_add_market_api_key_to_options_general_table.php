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
            $table->string('market_api_key', 255)->nullable()->after('azuriom_api_key');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('options_general', function (Blueprint $table) {
            $table->dropColumn('market_api_key');
        });
    }
};

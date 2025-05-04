<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        // Table options_general
        Schema::create('options_general', function (Blueprint $table) {
            $table->id();
            $table->boolean('email_verified')->default(0);
            $table->string('azuriom_url')->nullable();
            $table->boolean('mods_enabled')->default(1);
            $table->boolean('file_verification')->default(1);
            $table->boolean('embedded_java')->default(0);
            $table->string('game_folder_name', 100)->nullable();
            $table->integer('role_display')->nullable()->default(1);
            $table->integer('money_display')->nullable()->default(0);
            $table->integer('min_ram')->nullable()->default(2048);
            $table->integer('max_ram')->nullable()->default(4096);

            $table->timestamps();
        });

        // Table options_server
        Schema::create('options_server', function (Blueprint $table) {
            $table->id();
            $table->integer('server_id')->unique();
            $table->string('server_name');
            $table->string('server_ip');
            $table->string('server_port');
            $table->string('icon')->nullable();
            $table->string('type');
            $table->boolean('is_default')->default(false);
            $table->timestamps();
        });

        // Table options_rpc
        Schema::create('options_rpc', function (Blueprint $table) {
            $table->id();
            $table->boolean('rpc_activation')->default(1);
            $table->string('rpc_id', 100)->nullable();
            $table->text('rpc_details')->nullable()->default(''); // Valeur par défaut
            $table->text('rpc_state')->nullable()->default(''); // Valeur par défaut
            $table->text('rpc_large_text')->nullable()->default(''); // Valeur par défaut
            $table->text('rpc_small_text')->nullable()->default(''); // Valeur par défaut
            $table->text('rpc_button1')->nullable()->default(''); // Valeur par défaut
            $table->text('rpc_button1_url')->nullable()->default(''); // Valeur par défaut
            $table->text('rpc_button2')->nullable()->default(''); // Valeur par défaut
            $table->text('rpc_button2_url')->nullable()->default(''); // Valeur par défaut
            $table->timestamps();
        });

        // Table options_ui
        Schema::create('options_ui', function (Blueprint $table) {
            $table->id();
            $table->boolean('alert_activation')->default(0);
            $table->boolean('alert_scroll')->default(0);
            $table->text('alert_msg')->nullable()->default(''); // Valeur par défaut
            $table->boolean('video_activation')->default(0);
            $table->string('video_url', 255)->nullable()->default(''); // Valeur par défaut
            $table->text('splash')->nullable()->default(''); // Valeur par défaut
            $table->text('splash_author')->nullable()->default(''); // Valeur par défaut
            $table->timestamps();
        });

        // Table options_security
        Schema::create('options_security', function (Blueprint $table) {
            $table->id();
            $table->boolean('maintenance')->default(0);
            $table->boolean('whitelist')->default(0);
            $table->text('maintenance_message')->nullable()->default(''); // Valeur par défaut
            $table->timestamps();
        });

        // Table options_loader
        Schema::create('options_loader', function (Blueprint $table) {
            $table->id();
            $table->string('minecraft_version', 50)->nullable()->default(''); // Valeur par défaut
            $table->string('loader_type', 50)->nullable()->default(''); // Valeur par défaut
            $table->string('loader_build_version', 50)->nullable()->default(''); // Valeur par défaut
            $table->string('loader_forge_version', 50)->nullable()->default(''); // Valeur par défaut
            $table->boolean('loader_activation')->default(1);
            $table->timestamps();
        });

        // Table mods
        Schema::create('mods', function (Blueprint $table) {
            $table->id();
            $table->string('file', 255);
            $table->string('name', 255);
            $table->text('description')->nullable()->default(''); // Valeur par défaut
            $table->string('icon', 255)->nullable()->default(''); // Valeur par défaut
            $table->boolean('optional')->default(0);
            $table->boolean('recommended')->default(0);
            $table->timestamps();
        });

        // Table roles
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->text('role_name')->default(''); // Valeur par défaut
            $table->text('role_background')->default(''); // Valeur par défaut
            $table->timestamps();
        });

        // Table ignored_folders
        Schema::create('ignored_folders', function (Blueprint $table) {
            $table->text('folder_name')->default(''); // Valeur par défaut
            $table->id();
            $table->timestamps();
        });

        // Table whitelist
        Schema::create('whitelist', function (Blueprint $table) {
            $table->id();
            $table->text('users')->default(''); // Valeur par défaut
            $table->timestamps();
        });

        // Table whitelist_roles
        Schema::create('whitelist_roles', function (Blueprint $table) {
            $table->id();
            $table->text('role')->default(''); // Valeur par défaut
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('whitelist_roles');
        Schema::dropIfExists('whitelist');
        Schema::dropIfExists('ignored_folders');
        Schema::dropIfExists('roles');
        Schema::dropIfExists('mods');
        Schema::dropIfExists('options_loader');
        Schema::dropIfExists('options_security');
        Schema::dropIfExists('options_ui');
        Schema::dropIfExists('options_rpc');
        Schema::dropIfExists('options_server');
        Schema::dropIfExists('options_general');
    }
};

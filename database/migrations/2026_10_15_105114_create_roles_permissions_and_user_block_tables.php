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
        // 1. Add is_blocked and blocked_reason to users
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'is_blocked')) {
                $table->boolean('is_blocked')->default(false)->after('password');
            }
            if (!Schema::hasColumn('users', 'blocked_reason')) {
                $table->string('blocked_reason')->nullable()->after('is_blocked');
            }
        });

        // 2. Roles table
        if (!Schema::hasTable('roles')) {
            Schema::create('roles', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('slug')->unique();
                $table->text('description')->nullable();
                $table->boolean('is_system')->default(false);
                $table->timestamps();
            });
        }

        // 3. Permissions table
        if (!Schema::hasTable('permissions')) {
            Schema::create('permissions', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('slug')->unique();
                $table->string('module')->default('General');
                $table->timestamps();
            });
        }

        // 4. Role Permission Pivot table
        if (!Schema::hasTable('role_permission')) {
            Schema::create('role_permission', function (Blueprint $table) {
                $table->id();
                $table->foreignId('role_id')->constrained('roles')->cascadeOnDelete();
                $table->foreignId('permission_id')->constrained('permissions')->cascadeOnDelete();
                $table->unique(['role_id', 'permission_id']);
                $table->timestamps();
            });
        }

        // 5. Add role_id to admins table
        Schema::table('admins', function (Blueprint $table) {
            if (!Schema::hasColumn('admins', 'role_id')) {
                $table->foreignId('role_id')->nullable()->after('role')->constrained('roles')->nullOnDelete();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('admins', function (Blueprint $table) {
            if (Schema::hasColumn('admins', 'role_id')) {
                $table->dropForeign(['role_id']);
                $table->dropColumn('role_id');
            }
        });

        Schema::dropIfExists('role_permission');
        Schema::dropIfExists('permissions');
        Schema::dropIfExists('roles');

        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'blocked_reason')) {
                $table->dropColumn('blocked_reason');
            }
            if (Schema::hasColumn('users', 'is_blocked')) {
                $table->dropColumn('is_blocked');
            }
        });
    }
};

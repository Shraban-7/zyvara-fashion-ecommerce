<?php

use App\Enums\Gender;
use App\Enums\UserRole;
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
            $table->string('phone')->nullable()->unique()->after('email');
            $table->enum('role', UserRole::values())->default(UserRole::CUSTOMER->value)->after('password');
            $table->string('avatar')->nullable()->after('role');
            $table->enum('gender', Gender::values())->nullable()->after('avatar');
            $table->date('date_of_birth')->nullable()->after('gender');
            $table->boolean('is_active')->default(true)->after('date_of_birth');
            $table->boolean('is_phone_verified')->default(false)->after('is_active');
            $table->timestamp('phone_verified_at')->nullable()->after('is_phone_verified');
            $table->timestamp('last_login_at')->nullable()->after('phone_verified_at');
            $table->string('last_login_ip')->nullable()->after('last_login_at');
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'phone',
                'role',
                'avatar',
                'gender',
                'date_of_birth',
                'is_active',
                'is_phone_verified',
                'phone_verified_at',
                'last_login_at',
                'last_login_ip',
            ]);
            $table->dropSoftDeletes();
        });
    }
};

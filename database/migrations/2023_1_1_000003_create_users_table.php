<?php

namespace Database\Migrations;

use App\Enums\RoleEnum;
use Core\Database\{Blueprint, Migration, Schema};

return new class extends Migration {
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->foreignId('department_id')->nullable()->constrained()->nullOnDelete()->cascadeOnUpdate();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('role')->default(RoleEnum::EMPLOYEE->value);
            $table->rememberToken();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};

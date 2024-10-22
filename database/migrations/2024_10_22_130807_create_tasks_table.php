<?php

namespace Database\Migrations;

use App\Enums\TaskPriorityEnum;
use App\Enums\TaskStatusEnum;
use Core\Database\{Blueprint, Migration, Schema};

return new class extends Migration {
    public function up(): void
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('created_by')->constrained('users')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('assigned_to')->constrained('users')->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('title');
            $table->text('description');
            $table->string('priority')->default(TaskPriorityEnum::LOW->value);
            $table->string('status')->default(TaskStatusEnum::PENDING->value);
            $table->date('due_date')->nullable();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('meal_plans', function (Blueprint $table) {
            $table->id();
            $table->date('plan_date');
            $table->string('meal_type', 20);
            $table->unsignedInteger('planned_portion')->default(0);
            $table->unsignedInteger('prepared_portion')->default(0);
            $table->decimal('unit_cost', 14, 2)->default(0);
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->unique(['plan_date', 'meal_type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('meal_plans');
    }
};

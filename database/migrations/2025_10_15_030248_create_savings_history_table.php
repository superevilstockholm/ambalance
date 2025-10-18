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
        Schema::create('savings_history', function (Blueprint $table) {
            $table->id();
            $table->foreignId('savings_id')
                ->nullable()
                ->constrained('savings')
                ->onDelete('cascade');
            $table->foreignId('user_id')
                ->nullable()
                ->constrained('users')
                ->onDelete('cascade')
                ->comment('User with role teacher who make the transaction');
            $table->decimal('amount', 15, 2);
            $table->enum('type', ['in', 'out'])->default('in')->comment('in/out');
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('savings_history');
    }
};

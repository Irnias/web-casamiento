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
        Schema::create('guests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained()->onDelete('cascade');
            $table->string('invitation_code');
            $table->string('name');
            $table->enum('attendance', ['pending', 'confirmed', 'declined'])->default('pending');
            $table->text('dietary_restrictions')->nullable();
            $table->string('drink_preferences')->nullable();
            $table->boolean('is_child')->default(false);
            $table->timestamps();
            $table->unique(['event_id', 'invitation_code']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('guests');
    }
};

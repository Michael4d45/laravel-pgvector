<?php

use App\Models\Group;
use App\Models\Personality;
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
        Schema::create('group_personality', function (Blueprint $table) {
            $table->foreignIdFor(Group::class)->constrained()->onDelete('cascade');
            $table->foreignIdFor(Personality::class)->constrained()->onDelete('cascade');
            $table->primary(['group_id', 'personality_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('group_personality');
    }
};

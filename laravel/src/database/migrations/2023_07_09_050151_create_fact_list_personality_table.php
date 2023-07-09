<?php

use App\Models\FactList;
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
        Schema::create('fact_list_personality', function (Blueprint $table) {
            $table->foreignIdFor(FactList::class)->constrained()->onDelete('cascade');
            $table->foreignIdFor(Personality::class)->constrained()->onDelete('cascade');
            $table->primary(['fact_list_id', 'personality_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fact_personality');
    }
};

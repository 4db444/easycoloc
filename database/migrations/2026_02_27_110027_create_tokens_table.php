<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tokens', function (Blueprint $table) {
            $table->id();
            $table->string("token");
            $table->unsignedBigInteger("group_id");
            $table->foreign("group_id")->references("id")->on("groups");
            $table->timestamp("expire_at");
            $table->timestamps();
        });
    }
    
    public function down(): void
    {
        Schema::dropIfExists('tokens');
    }
};

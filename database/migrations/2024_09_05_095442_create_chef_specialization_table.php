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
        Schema::create('chef_specialization', function (Blueprint $table) {
            $table->unsignedBigInteger('chef_id');
            $table  ->foreign('chef_id')
                    ->references('id')
                    ->on('chefs')
                    ->onDelete('cascade');

            $table->unsignedBigInteger('specialization_id');
            $table  ->foreign('specialization_id')
                    ->references('id')
                    ->on('specializations')
                    ->onDelete('cascade');

            $table->primary(['chef_id', 'specialization_id']);


        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chef_specialization');
    }
};

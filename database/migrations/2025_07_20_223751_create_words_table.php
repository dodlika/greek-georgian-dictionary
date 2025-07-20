<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('words', function (Blueprint $table) {
            $table->id();
            $table->string('greek_word');
            $table->string('greek_present')->nullable();
            $table->string('greek_past')->nullable();
            $table->string('greek_future')->nullable();
            $table->string('georgian_translation');
            $table->string('word_type')->default('verb'); // verb, noun, adjective, etc.
            $table->timestamps();
            
            // Add indexes for search
            $table->index('greek_word');
            $table->index('georgian_translation');
        });
    }

    public function down()
    {
        Schema::dropIfExists('words');
    }
};
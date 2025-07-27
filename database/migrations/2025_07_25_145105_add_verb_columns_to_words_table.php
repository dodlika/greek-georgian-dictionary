<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
 public function up()
{
    Schema::table('words', function (Blueprint $table) {
        $table->text('english_translation')->nullable();
        $table->json('present_tense')->nullable();
        $table->json('past_tense')->nullable();
        $table->json('future_tense')->nullable();
    });
}

public function down()
{
    Schema::table('words', function (Blueprint $table) {
        $table->dropColumn(['english_translation', 'present_tense', 'past_tense', 'future_tense']);
    });
}

};

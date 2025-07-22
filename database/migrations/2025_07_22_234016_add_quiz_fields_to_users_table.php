<?php
// database/migrations/2024_01_01_120000_add_quiz_fields_to_users_table.php
// Create this with: php artisan make:migration add_quiz_fields_to_users_table --table=users

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            // Only add columns if they don't exist
            if (!Schema::hasColumn('users', 'best_quiz_score')) {
                $table->integer('best_quiz_score')->default(0);
            }
            if (!Schema::hasColumn('users', 'best_quiz_total')) {
                $table->integer('best_quiz_total')->default(0);
            }
            if (!Schema::hasColumn('users', 'best_quiz_date')) {
                $table->timestamp('best_quiz_date')->nullable();
            }
            if (!Schema::hasColumn('users', 'total_quizzes_taken')) {
                $table->integer('total_quizzes_taken')->default(0);
            }
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $columns = ['best_quiz_score', 'best_quiz_total', 'best_quiz_date', 'total_quizzes_taken'];
            foreach ($columns as $column) {
                if (Schema::hasColumn('users', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
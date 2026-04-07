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
        if (Schema::hasTable('good_moral_applications')) {
            Schema::table('good_moral_applications', function (Blueprint $table) {
                if (!Schema::hasColumn('good_moral_applications', 'gender')) {
                    $table->enum('gender', ['male', 'female'])->after('fullname');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('good_moral_applications')) {
            Schema::table('good_moral_applications', function (Blueprint $table) {
                if (Schema::hasColumn('good_moral_applications', 'gender')) {
                    $table->dropColumn('gender');
                }
            });
        }
    }
};

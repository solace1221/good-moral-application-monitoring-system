<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Update existing deansom and deangradsch accounts to regular dean accounts
        // since SOM and GRADSCH are now handled as departments
        DB::table('role_account')
            ->whereIn('account_type', ['deansom', 'deangradsch'])
            ->update(['account_type' => 'dean']);

        // Log the changes
        $updated = DB::table('role_account')
            ->where('account_type', 'dean')
            ->whereIn('department', ['SOM', 'GRADSCH'])
            ->count();

        if ($updated > 0) {
            \Log::info("Updated {$updated} dean accounts from deansom/deangradsch to dean");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Restore the specific dean types based on department
        DB::table('role_account')
            ->where('account_type', 'dean')
            ->where('department', 'SOM')
            ->update(['account_type' => 'deansom']);

        DB::table('role_account')
            ->where('account_type', 'dean')
            ->where('department', 'GRADSCH')
            ->update(['account_type' => 'deangradsch']);
    }
};

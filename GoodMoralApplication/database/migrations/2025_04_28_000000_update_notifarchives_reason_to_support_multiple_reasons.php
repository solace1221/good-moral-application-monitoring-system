<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class UpdateNotifarchivesReasonToSupportMultipleReasons extends Migration
{
    public function up()
    {
        if (Schema::hasTable('notifarchives')) {
            // First, let's backup existing single reasons and convert them to JSON array format
            DB::statement("UPDATE notifarchives SET reason = CONCAT('[\"', REPLACE(reason, '\"', '\\\"'), '\"]') WHERE reason IS NOT NULL AND reason != ''");

            // Change the column type to JSON
            Schema::table('notifarchives', function (Blueprint $table) {
                $table->json('reason')->change();
            });
        }
    }

    public function down()
    {
        if (Schema::hasTable('notifarchives')) {
            // Convert JSON back to single string (take first reason if multiple exist)
            $notifications = DB::table('notifarchives')->get();

            foreach ($notifications as $notification) {
                if ($notification->reason) {
                    $reasons = json_decode($notification->reason, true);
                    if (is_array($reasons) && !empty($reasons)) {
                        $firstReason = $reasons[0];
                        DB::table('notifarchives')
                            ->where('id', $notification->id)
                            ->update(['reason' => $firstReason]);
                    }
                }
            }

            // Change back to string
            Schema::table('notifarchives', function (Blueprint $table) {
                $table->string('reason')->change();
            });
        }
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class UpdateReasonToSupportMultipleReasons extends Migration
{
    public function up()
    {
        if (Schema::hasTable('good_moral_applications')) {
            // First, let's backup existing single reasons and convert them to JSON array format
            DB::statement("UPDATE good_moral_applications SET reason = CONCAT('[\"', REPLACE(reason, '\"', '\\\"'), '\"]') WHERE reason IS NOT NULL AND reason != ''");

            // Change the column type to JSON
            Schema::table('good_moral_applications', function (Blueprint $table) {
                $table->json('reason')->change();
            });
        }
    }

    public function down()
    {
        if (Schema::hasTable('good_moral_applications')) {
            // Convert JSON back to single string (take first reason if multiple exist)
            $applications = DB::table('good_moral_applications')->get();

            foreach ($applications as $application) {
                if ($application->reason) {
                    $reasons = json_decode($application->reason, true);
                    if (is_array($reasons) && !empty($reasons)) {
                        $firstReason = $reasons[0];
                        DB::table('good_moral_applications')
                            ->where('id', $application->id)
                            ->update(['reason' => $firstReason]);
                    }
                }
            }

            // Change back to string
            Schema::table('good_moral_applications', function (Blueprint $table) {
                $table->string('reason')->change();
            });
        }
    }
}

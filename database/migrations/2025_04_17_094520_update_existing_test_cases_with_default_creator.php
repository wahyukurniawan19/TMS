<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class UpdateExistingTestCasesWithDefaultCreator extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Get the first user
        $firstUser = DB::table('users')->first();
        
        if ($firstUser) {
            // Update existing test cases with the first user's ID
            DB::table('test_cases')
                ->whereNull('created_by')
                ->update([
                    'created_by' => $firstUser->id,
                    'updated_by' => $firstUser->id
                ]);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // No need to reverse this migration
    }
}

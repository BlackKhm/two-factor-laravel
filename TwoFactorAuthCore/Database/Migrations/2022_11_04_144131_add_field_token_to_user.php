<?php

use Illuminate\Database\Migrations\Migration;
use Modules\TwoFactorAuthCore\Services\TwoFactorMigrateService;

class AddFieldTokenToUser extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        resolve(TwoFactorMigrateService::class)->up();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        resolve(TwoFactorMigrateService::class)->down();
    }
}

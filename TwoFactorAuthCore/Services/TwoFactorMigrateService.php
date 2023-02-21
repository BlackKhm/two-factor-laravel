<?php

namespace Modules\TwoFactorAuthCore\Services;

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Modules\TwoFactorAuthCore\Services\TwoFactorService;

class TwoFactorMigrateService
{
    /**
     * Two Factor Service
     *
     * @var TwoFactorService
     */
    protected $tfService;

    /**
     * __construct
     *
     * @param TwoFactorService $tfService
     */
    public function __construct(TwoFactorService $tfService)
    {
        $this->tfService = $tfService;
    }
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table($this->tfService->table(), function (Blueprint $table) {
            if (!Schema::hasColumn($this->tfService->table(), $this->tfService->personalToken())) {
                $table->text($this->tfService->personalToken())->nullable();
            }
            if (!Schema::hasColumn($this->tfService->table(), $this->tfService->personalTokenExpire())) {
                $table->timestamp($this->tfService->personalTokenExpire())->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table($this->tfService->table(), function (Blueprint $table) {
            if (Schema::hasColumn($this->tfService->table(), $this->tfService->personalToken())) {
                $table->dropColumn([$this->tfService->personalToken()]);
            }

            if (Schema::hasColumn($this->tfService->table(), $this->tfService->personalTokenExpire())) {
                $table->dropColumn([$this->tfService->personalTokenExpire()]);
            }
        });
    }
}

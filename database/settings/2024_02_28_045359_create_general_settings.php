<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {


        $this->migrator->add('general.maintenance_mode', '0');
        $this->migrator->add('general.force_update', '0');
        $this->migrator->add('general.ad_network', 'admob');
        $this->migrator->add('general.mining_function', '0');
        $this->migrator->add('general.referral_coin', '0');
        $this->migrator->add('general.joining_coin', '0');
        $this->migrator->add('general.sm_country_t_charge', '0');
        $this->migrator->add('general.diff_country_t_charge', '0');
        $this->migrator->add('general.version_code', '3.0');
        $this->migrator->add('general.coin_valuation', '0.014');
    }
};

<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class GeneralSettings extends Settings
{
    public string $maintenance_mode;
    public string $force_update;
    public string $ad_network;
    public string $referral_coin;
    public string $joining_coin;
    public string $sm_country_t_charge;
    public string $diff_country_t_charge;
    public string $mining_function;
    public string $version_code;
    public string $coin_valuation;
    public static function group(): string
    {
        return 'general';
    }
}
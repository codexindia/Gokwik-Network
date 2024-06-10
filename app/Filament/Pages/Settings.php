<?php

namespace App\Filament\Pages;


use Closure;
use Filament\Forms\Components\{Group, Section};
use Filament\Forms\Components\{Select, Radio, TextInput};
use Filament\Forms\Form;
use Filament\Forms\Set;


use Filament\Pages\Page;
use App\Settings\GeneralSettings;
use Filament\Pages\SettingsPage;
use Str;



class Settings extends SettingsPage
{
    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';
    protected static ?int $navigationSort = 3;

    protected static string $settings = GeneralSettings::class;

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Group::make()->schema([
                    Section::make('General options')->schema([
                        Select::make('maintenance_mode')
                            ->required()->options([
                                '1' => 'Enable',
                                '0' => 'Disable'
                            ])->prefixIcon('heroicon-o-power'),

                        Select::make('force_update')
                            ->required()->options([
                                '1' => 'Enable',
                                '0' => 'Disable'
                            ])->prefixIcon('heroicon-o-device-phone-mobile'),

                        TextInput::make('version_code')
                            ->numeric()
                            ->label('Version Code')
                            ->prefixIcon('heroicon-o-arrow-trending-up'),

                     



                        Select::make('ad_network')->options([
                            'admob' => 'Admob',
                        ])->default('admob')->prefixIcon('heroicon-s-signal'),
                        Radio::make('mining_function')
                            ->label('Mining Function')
                            ->boolean()
                            ->inline()
                            ->default(1)
                            ->inlineLabel(false),

                    ])->columns(2)

                ]),
                Group::make()->schema([
                    Section::make('Important Settings')->schema([

                        TextInput::make('referral_coin')->prefixIcon('heroicon-s-gift')
                            ->required()->integer(),
                        TextInput::make('joining_coin')->prefixIcon('heroicon-s-gift')
                            ->required()->integer(),
                        TextInput::make('sm_country_t_charge')
                            ->required()->label('Same Country Transfer Charges')->prefixIcon('heroicon-o-currency-dollar')
                            ->integer(),
                        TextInput::make('diff_country_t_charge')
                            ->required()->label('Other Country Transfer Charges')->prefixIcon('heroicon-o-currency-dollar')
                            ->integer(),
                            TextInput::make('coin_valuation')
                            ->numeric()
                            ->label('Coin Valuation')
                            ->prefix('USD'),
                            ])->columns(2)

                ])

            ])->columns(2);
    }

    // public function save()
    // {

    //     Notification::make()
    //         ->success()
    //         ->title("Changes Saved SuccessFully")
    //         ->send();
    // }
}

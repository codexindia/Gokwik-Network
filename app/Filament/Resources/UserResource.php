<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Models\CountryInfo;
use App\Models\User;
use Filament\Forms\{Form};
use Filament\Forms\Components\{Group, Section, TextInput, DatePicker, FileUpload, Select};

use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Table;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Columns\TextColumn;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Group::make()->schema([
                    Section::make()->schema([
                        TextInput::make('name')->required(),
                        TextInput::make('email')->unique(ignoreRecord: true)->required(),
                        TextInput::make('username')->required()->unique(ignoreRecord: true),
                        TextInput::make('phone_number')->prefix(function (User $record): string {
                            return "+" . $record->country_code;
                        })->hidden(function ($context) {
                            return $context == 'create';
                        })->required()->numeric()->unique(ignoreRecord: true),
                        Select::make('country_code')->options(CountryInfo::all()->pluck('name', 'id'))->hidden(fn ($context): bool => $context == 'edit')->searchable()->required(),
                        TextInput::make('phone_number')->hidden(function ($context) {
                            return $context == 'edit';
                        })->required()->numeric()->unique(),
                        DatePicker::make('date_of_birth')->label('Date Of Birth')->required()
                    ])->columns(2)
                ]),
                //profile pic

                Group::make()->schema([
                    Section::make('Profile Picture')->schema([
                        FileUpload::make('profile_pic')->required()->image()->directory('users/profile')->imageEditor(),
                    ])->collapsible()
                ])

            ]);
    }

    public static function table(Table $table): Table
    {

        return $table
            ->columns([
                ImageColumn::make('profile_pic')
                    ->circular(),
                TextColumn::make('name')->searchable(),
                TextColumn::make('username')->copyMessage('Username Copied SuccessFully')->copyable()->searchable(),

                TextColumn::make('Country.name'),
                TextColumn::make('phone_number')
                    ->prefix(
                        function (User $record): string {
                            return "+" . $record->country_code;
                        }
                    ),
                TextColumn::make('created_at')->dateTime('h:i:sa d-m-y')
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])->searchPlaceholder('Search (Name,Email,Phone Number)');;
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }
    protected function getHeaderActions(): array
    {
        return [];
    }
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}

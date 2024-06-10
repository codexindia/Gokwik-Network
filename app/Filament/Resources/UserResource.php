<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Models\CountryInfo;
use App\Models\User;
use Filament\Tables\Actions\ActionGroup;

use Filament\Tables\Actions\Action;

use Filament\Forms\{Form};
use Filament\Forms\Components\{Group, Section, TextInput, DatePicker, FileUpload, Select, Textarea};
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Table;


use Filament\Tables\Columns\TextColumn;
use App\Models\BlockedUser;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';
    public static function canCreate(): bool
    {
        return false;
    }

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
                        DatePicker::make('date_of_birth')->label('Date Of Birth')->required(),
                        TextInput::make('coin')->label('Available Coins')->readOnly(),
                    ])->columns(2)
                ]),
                //profile pic

                Group::make()->schema([
                    Section::make('Profile Picture')->schema([
                        FileUpload::make('profile_pic')->image()->directory('users/profile')->imageEditor(),
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
                TextColumn::make('created_at')->dateTime('h:i:sa d-m-y'),

            ])

            ->defaultSort('created_at', 'desc')
            ->filters([
                //
            ])
            ->actions([
                ActionGroup::make([
                    Action::make('Ban')
                        ->requiresConfirmation()
                        ->form([
                            Textarea::make('Reason')
                                ->required(),
                        ])
                        ->action(fn (array $data, User $record) =>  self::blockuser($record, 1, $data))
                        ->hidden(fn (User $record) => $record->GetBlockStatus != null)
                        ->icon('heroicon-m-no-symbol'),
                    Action::make('Unban')

                        ->requiresConfirmation()
                        ->modalDescription(fn (User $record) => "Do You Want To Unban The User \n Ban Reason : " . $record->GetBlockStatus->reason)
                        ->action(fn (User $record) =>  self::blockuser($record, 0))
                        ->hidden(fn (User $record) => $record->GetBlockStatus == null)
                        ->icon('heroicon-m-no-symbol'),

                    Tables\Actions\EditAction::make(),
                ])->label('Actions')
                    ->button()


            ])

            ->searchPlaceholder('Search (Name,Email,Phone Number)');;
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
    public static function blockuser(User $user, $status, $data = array())
    {
        if ($status) {
            $block = new BlockedUser;
            $block->user_id = $user->id;
            $block->reason = $data['Reason'];
            $block->save();
            Notification::make()
                ->success()
                ->title("User Block SuccessFully")
                ->send();
        } else {
            BlockedUser::where('user_id', $user->id)->delete();
            Notification::make()
                ->success()
                ->title("User Unblock SuccessFully")
                ->send();
        }
    }
}

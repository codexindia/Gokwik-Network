<?php

namespace App\Filament\Pages;



use App\Models\User;
use Filament\Actions\Action;
use Filament\Forms\Components\{FileUpload, Group, Section, Textarea, TextInput};
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;

use Filament\Support\View\Components\Modal;
use Filament\Tables\Actions\Contracts\HasTable;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Table;

class PushNotification extends Page
{





    protected static ?int $navigationSort = 3;
    protected static ?string $navigationIcon = 'heroicon-o-paper-airplane';

    protected static string $view = 'filament.pages.push-notification';
    protected function getHeaderActions(): array
    {
        return [];
    }
    public $data = [];
    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Group::make()->schema([
                    Section::make('Banner Image')->schema([
                        FileUpload::make('image')
                            ->image()
                            ->directory('users/push_notification')
                            ->label('Banner Image'),
                    ]),

                ]),
                Group::make()->schema([
                    Section::make('General options')->schema([
                        TextInput::make('title')->required(),

                        Textarea::make('message')->columnSpanFull()
                    ]),

                ]),
            ])->columns(2)->statePath('data');;
    }

    protected function getActions(): array
    {
        return [
            Action::make('Push Notification')
                ->icon('heroicon-o-paper-airplane')

                ->submit('save')
                ->requiresConfirmation()
                ->keyBindings(['command+s', 'ctrl+s'])



        ];
    }
    public function save()
    {
        if ($this->form->getState()['image'] != null) {


            $params['big_picture'] = url('/storage/' . $this->form->getState()['image']);
        }
        sendpush(null, $this->form->getState()['message'], $this->form->getState()['title'], $params);

        Notification::make()
            ->title('Notification Pushed SuccessFully')
            ->success()
            ->send();
    }
}

<?php

namespace App\Filament\Pages;

use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Forms\Components\Actions;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\ToggleButtons;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Support\Enums\ActionSize;
use Filament\Support\Enums\Alignment;
use App\Models\PopupBanner;
class PopupImagePage extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $title = 'Popup Manager';


    protected static string $view = 'filament.pages.popup-image-page';
    public ?array $data = [];
    public $popupImage;
    public function mount(PopupBanner $PopupBanner): void
{
    $this->form->fill($PopupBanner->first()->toArray());
}
protected static $ignoreAccessors = true;

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Group::make()->schema([
                    Section::make('Banner Image')->schema([
                        FileUpload::make('image')
                            ->image()
                            ->directory('users/popup')
                            ->label('Popup Image'),
                    ]),

                ]),
                Group::make()->schema([
                    Section::make('General options')->schema([
                        TextInput::make('button_text')->required(),
                        TextInput::make('action_link')->required(),
                        ToggleButtons::make('visibility')
                       
                        ->boolean()
                        ->inline()


                    ])->columns(2),

                ]),
            ])->columns(2) ->statePath('data');;
    }
    protected function getActions(): array
    {
        return [
            Action::make('Save And Update')
                ->icon('heroicon-m-star')->requiresConfirmation()
                ->submit('save'),

        ];
    }
    protected function getHeaderActions(): array
    {
        return [];
    }

    public function save(): void
    {
        PopupBanner::first()->update($this->form->getState());
      
        Notification::make()
            ->title('Saved successfully')
            ->success()
            ->send();
    }
}

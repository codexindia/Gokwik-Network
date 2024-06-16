<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use App\Models\User;
use Filament\Resources\Pages\Concerns\InteractsWithRecord;
use Filament\Resources\Pages\Page;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Spatie\Activitylog\Models\Activity;



class ListUserActivities extends page implements HasTable
{
    use InteractsWithTable;

    protected static string $resource = UserResource::class;

    protected static string $view = 'filament.pages.activity';
    protected $record;
    public function mount($record = null): void
    {
        $this->record = $record;
    }



    public  function table(Table $table): Table
    {

        return $table
            ->query(Activity::query()->whereHasMorph('causer', [\App\Models\User::class])->where('causer_id',$this->record)->orderBy('id', 'desc'))
            ->columns([
                TextColumn::make('event')
                    ->label('Event')

                    ->sortable(),
                TextColumn::make('description')

                    ->sortable(),
                TextColumn::make('subject_type')
                    ->label('Subject Type')

                    ->sortable(),

                TextColumn::make('properties')
                    ->label('Changes')
                    ->formatStateUsing(fn ($state) => self::formatProperties($state))
                    ->html()
                    ->size(TextColumn\TextColumnSize::ExtraSmall),
                TextColumn::make('created_at')
                    ->label('Date')
                    ->dateTime()
                    ->sortable()
                    ->since(),

            ]);
    }
    public static function formatProperties($properties)
    {
        if (is_string($properties)) {
            $properties = json_decode($properties, true);
        }

        if (!is_array($properties)) {
            return $properties;
        }

        $output = '<ul>';
        foreach ($properties as $key => $value) {
            $output .= "<li><strong>$key:</strong> " . (is_array($value) ? json_encode($value) : $value) . "</li>";
        }
        $output .= '</ul>';

        return $output;
    }
}

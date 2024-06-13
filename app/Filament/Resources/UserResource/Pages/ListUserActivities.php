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
use Illuminate\Support\Str;
use Spatie\Activitylog\Models\Activity;



class ListUserActivities extends page implements HasTable
{
    use InteractsWithTable;

    protected static string $resource = UserResource::class;

    protected static string $view = 'filament.pages.activity';

    public static function table(Table $table): Table
    {

        return $table
            ->query(Activity::query()->whereHasMorph('causer', [\App\Models\User::class]))
            ->columns([
                TextColumn::make('event')
                    ->label('Event')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('description')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('subject_type')
                    ->label('Subject Type')
                    ->searchable()
                    ->sortable(),
               
                TextColumn::make('properties')
                    ->label('Changes')
                    ->formatStateUsing(fn ($state) => self::formatProperties($state))
                    ->html()
                    ->searchable() ->size(TextColumn\TextColumnSize::ExtraSmall),
                    TextColumn::make('created_at')
                    ->label('Date')
                    ->dateTime()
                    ->sortable()
                    ->since()
                    ,
                    
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

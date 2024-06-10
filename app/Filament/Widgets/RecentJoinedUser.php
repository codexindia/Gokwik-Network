<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\UserResource;
use App\Models\User;

use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\TextColumn\TextColumnSize;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;

class RecentJoinedUser extends BaseWidget
{
  

    public function table(Table $table): Table
    {
        return $table
            ->defaultSort('id', 'desc')
            ->query(User::query()->latest()->limit(5))
            ->columns([
                TextColumn::make('name')->size(TextColumnSize::ExtraSmall),
                TextColumn::make('username')->size(TextColumnSize::ExtraSmall)->copyMessage('Username Copied SuccessFully')->copyable(),

                TextColumn::make('Country.name')->size(TextColumnSize::ExtraSmall),
                TextColumn::make('created_at')->size(TextColumnSize::ExtraSmall)->since(),
            ])->paginated(false);
    }
}

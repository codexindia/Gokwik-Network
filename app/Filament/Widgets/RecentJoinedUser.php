<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\UserResource;
use App\Models\User;

use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
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
                TextColumn::make('name'),
                TextColumn::make('username')->copyMessage('Username Copied SuccessFully')->copyable(),

                TextColumn::make('Country.name'),
                TextColumn::make('created_at')->since(),
            ])->paginated(false);
    }
}

<?php

declare(strict_types=1);

namespace App\Filament\Resources\Sections\Tables;

use App\Models\Section;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;

class SectionsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->paginated(false)
            ->columns([
                TextColumn::make('type')->badge()->formatStateUsing(fn (string $state): string => Section::label($state)),
                ToggleColumn::make('is_visible')->label('Visible'),
                TextColumn::make('updated_at')->since(),
            ])
            ->recordActions([
                EditAction::make(),
            ]);
    }
}

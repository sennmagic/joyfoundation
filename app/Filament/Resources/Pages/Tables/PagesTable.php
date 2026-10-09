<?php

declare(strict_types=1);

namespace App\Filament\Resources\Pages\Tables;

use App\Models\Page;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;

class PagesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')->searchable(),
                TextColumn::make('slug')->prefix('/'),
                TextColumn::make('sections_count')->counts('sections')->label('Sections'),
                ToggleColumn::make('is_published')->label('Published'),
                TextColumn::make('updated_at')->since(),
            ])
            ->recordActions([
                Action::make('view')->label('View')->icon(Heroicon::OutlinedArrowTopRightOnSquare)->url(fn (Page $record): string => route('page', $record->slug))->openUrlInNewTab(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}

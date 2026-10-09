<?php

declare(strict_types=1);

namespace App\Filament\Resources\Pages\RelationManagers;

use App\Filament\Resources\Sections\Schemas\SectionForm;
use App\Models\Section;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;

class SectionsRelationManager extends RelationManager
{
    protected static string $relationship = 'sections';

    public function form(Schema $schema): Schema
    {
        return SectionForm::configure($schema);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('type')
            ->reorderable('sort_order')
            ->defaultSort('sort_order')
            ->paginated(false)
            ->columns([
                ImageColumn::make('preview')
                    ->state(fn (Section $record): string => SectionForm::previewUrl($record->type))
                    ->imageWidth(120)
                    ->imageHeight(60)
                    ->label(''),
                TextColumn::make('type')->badge()->formatStateUsing(fn (string $state): string => Section::label($state)),
                ToggleColumn::make('is_visible')->label('Visible'),
                TextColumn::make('updated_at')->since(),
            ])
            ->headerActions([
                CreateAction::make()->label('Add section'),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}

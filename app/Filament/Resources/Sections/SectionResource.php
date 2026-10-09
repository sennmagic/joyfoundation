<?php

declare(strict_types=1);

namespace App\Filament\Resources\Sections;

use App\Filament\Resources\Sections\Pages\EditSection;
use App\Filament\Resources\Sections\Pages\ListSections;
use App\Filament\Resources\Sections\Schemas\SectionForm;
use App\Filament\Resources\Sections\Tables\SectionsTable;
use App\Models\Section;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

/** Site-wide settings (nav, social). Page sections are managed inside each Page. */
class SectionResource extends Resource
{
    protected static ?string $model = Section::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCog6Tooth;

    protected static ?string $navigationLabel = 'Site Settings';

    protected static ?string $modelLabel = 'setting';

    protected static ?int $navigationSort = 2;

    public static function form(Schema $schema): Schema
    {
        return SectionForm::configure($schema, Section::SETTINGS);
    }

    public static function table(Table $table): Table
    {
        return SectionsTable::configure($table);
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->whereNull('page_id');
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function getPages(): array
    {
        return [
            'index' => ListSections::route('/'),
            'edit' => EditSection::route('/{record}/edit'),
        ];
    }
}

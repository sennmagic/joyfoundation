<?php

declare(strict_types=1);

namespace App\Filament\Resources\Pages;

use App\Filament\Resources\Pages\Pages\CreatePage;
use App\Filament\Resources\Pages\Pages\EditPage;
use App\Filament\Resources\Pages\Pages\ListPages;
use App\Filament\Resources\Pages\RelationManagers\SectionsRelationManager;
use App\Filament\Resources\Pages\Schemas\PageForm;
use App\Filament\Resources\Pages\Tables\PagesTable;
use App\Models\Page;
use BackedEnum;
use Filament\Navigation\NavigationItem;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Schema as DbSchema;

class PageResource extends Resource
{
    protected static ?string $model = Page::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedDocumentText;

    protected static ?int $navigationSort = 1;

    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Schema $schema): Schema
    {
        return PageForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PagesTable::configure($table);
    }

    /** Lists every page under "Pages" in the sidebar so each editor is one click away. */
    public static function getNavigationItems(): array
    {
        $children = DbSchema::hasTable('pages')
            ? Page::query()->orderBy('title')->get()->map(function (Page $page): NavigationItem {
                $url = static::getUrl('edit', ['record' => $page]);

                return NavigationItem::make($page->title)
                    ->url($url)
                    ->isActiveWhen(fn (): bool => request()->url() === $url)
                    ->badge($page->is_published ? null : 'Draft', 'gray');
            })
            : collect();

        $createUrl = static::getUrl('create');

        $children->push(
            NavigationItem::make('+ New page')
                ->url($createUrl)
                ->isActiveWhen(fn (): bool => request()->url() === $createUrl),
        );

        return [
            parent::getNavigationItems()[0]->childItems($children->all()),
        ];
    }

    public static function getRelations(): array
    {
        return [
            SectionsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPages::route('/'),
            'create' => CreatePage::route('/create'),
            'edit' => EditPage::route('/{record}/edit'),
        ];
    }
}

<?php

declare(strict_types=1);

namespace App\Filament\Resources\Enquiries;

use App\Filament\Resources\Enquiries\Pages\ManageEnquiries;
use App\Models\Enquiry;
use BackedEnum;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class EnquiryResource extends Resource
{
    protected static ?string $model = Enquiry::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedInbox;

    protected static ?int $navigationSort = 3;

    protected static ?string $recordTitleAttribute = 'name';

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canEdit(Model $record): bool
    {
        return false;
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('name'),
                TextEntry::make('email')->copyable(),
                TextEntry::make('topic')->formatStateUsing(fn (string $state): string => Enquiry::TOPICS[$state] ?? $state),
                TextEntry::make('created_at')->label('Received')->dateTime(),
                TextEntry::make('message')->columnSpanFull()->extraAttributes(['style' => 'white-space: pre-wrap; overflow-wrap: anywhere;']),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('name')->searchable()->wrap(),
                TextColumn::make('email')->searchable()->wrap(),
                TextColumn::make('topic')->formatStateUsing(fn (string $state): string => Enquiry::TOPICS[$state] ?? $state),
                TextColumn::make('created_at')->label('Received')->dateTime()->sortable(),
                ToggleColumn::make('is_read')->label('Read'),
            ])
            ->filters([
                TernaryFilter::make('is_read')->label('Read status')->trueLabel('Read')->falseLabel('Unread'),
            ])
            ->recordActions([
                ViewAction::make(),
                DeleteAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageEnquiries::route('/'),
        ];
    }
}

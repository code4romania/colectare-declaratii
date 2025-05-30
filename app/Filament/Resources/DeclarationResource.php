<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Enums\DeclarationType;
use App\Filament\Resources\DeclarationResource\Pages;
use App\Models\Declaration;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class DeclarationResource extends Resource
{
    protected static ?string $model = Declaration::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('county_id')
                    ->relationship('county', 'name')
                    ->label(__('app.fields.county')),
                Select::make('locality_id')
                    ->relationship('locality', 'name')
                    ->label(__('app.fields.locality')),

                Radio::make('type')
                    ->required()
                    ->options(DeclarationType::options()),

                TextInput::make('full_name')
                    ->maxLength(255),
                TextInput::make('institution')
                    ->maxLength(255),
                TextInput::make('position')
                    ->maxLength(255),

                FileUpload::make('file')
                    ->disk('s3')
                    ->label(__('app.fields.file'))
                    ->acceptedFileTypes(['application/pdf'])
                    ->required(),

                TextInput::make('ip_address')
                    ->required()
                    ->maxLength(45),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('county.name')
                    ->sortable(),
                TextColumn::make('locality.name')
                    ->sortable(),

                TextColumn::make('type')
                    ->badge()
                    ->searchable(),

                TextColumn::make('full_name')
                    ->searchable(),

                TextColumn::make('institution')
                    ->wrap()
                    ->searchable(),

                TextColumn::make('position')
                    ->wrap()
                    ->searchable(),

                TextColumn::make('ip_address')
                    ->searchable(),

                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('type')
                    ->options(DeclarationType::options())
                    ->label(__('app.fields.type')),

                SelectFilter::make('county_id')
                    ->relationship('county', 'name', modifyQueryUsing: fn(Builder $query) => $query->whereHas('declartions'))
                    ->searchable()
                    ->multiple()
                    ->preload()
                    ->label(__('app.fields.county')),

                SelectFilter::make('locality_id')
                    ->relationship('locality', 'name', modifyQueryUsing: fn(Builder $query) => $query->whereHas('declartions'))
                    ->searchable()
                    ->multiple()
                    ->label(__('app.fields.locality')),

            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDeclarations::route('/'),
            'create' => Pages\CreateDeclaration::route('/create'),
            'edit' => Pages\EditDeclaration::route('/{record}/edit'),
        ];
    }
}

<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SignboardResource\Pages;
use App\Filament\Resources\SignboardResource\RelationManagers;
use App\Models\Signboard;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SignboardResource extends Resource
{
    protected static ?string $model = Signboard::class;

    protected static ?string $navigationIcon = 'simpleline-direction';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('business_id')
                    ->relationship('business', 'name')
                    ->required(),
                Forms\Components\Select::make('region_id')
                    ->relationship('region', 'name')
                    ->required(),
                Forms\Components\TextInput::make('town')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('street')
                    ->maxLength(255),
                Forms\Components\TextInput::make('landmark')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('blk_number')
                    ->maxLength(255),
                Forms\Components\TextInput::make('gps')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('business.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('region.name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('town')
                    ->searchable(),
                Tables\Columns\TextColumn::make('street')
                    ->searchable(),
                Tables\Columns\TextColumn::make('landmark')
                    ->searchable(),
                Tables\Columns\TextColumn::make('blk_number')
                    ->searchable(),
                Tables\Columns\TextColumn::make('gps')
                    ->searchable(),
                Tables\Columns\TextColumn::make('gps_lat')
                    ->searchable(),
                Tables\Columns\TextColumn::make('gps_lon')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('created On')
                    ->dateTime()
                    ->sortable()
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageSignboards::route('/'),
            'view' => Pages\ViewSignboard::route('/{record}'),
        ];
    }

    public static function can(string $action, \Illuminate\Database\Eloquent\Model|null $record = null): bool
    {
        return true;
    }
}

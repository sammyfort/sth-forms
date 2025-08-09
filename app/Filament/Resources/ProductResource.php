<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Filament\Resources\ProductResource\RelationManagers;
use App\Models\Product;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'zondicon-shopping-cart';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->relationship('user', 'firstname')
                    ->getOptionLabelFromRecordUsing(fn (User $record) => "{$record->firstname} {$record->lastname}")
                    ->required(),
                Forms\Components\TextInput::make('name')
                    ->label('Product name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('status')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Textarea::make('short_description')
                    ->required()
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('description')
                    ->required()
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('website')
                    ->maxLength(255),
                Forms\Components\TextInput::make('price')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Toggle::make('is_negotiable')
                    ->required(),
                Forms\Components\TextInput::make('first_mobile')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('second_mobile')
                    ->maxLength(255),
                Forms\Components\TextInput::make('whatsapp_mobile')
                    ->maxLength(255),
                Forms\Components\Select::make('region_id')
                    ->relationship('region', 'name')
                    ->required(),
                Forms\Components\TextInput::make('town')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('categories')
                    ->relationship('categories', 'name')
                    ->required(),
                Forms\Components\TextInput::make('video_link')
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.fullname')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('price')
                    ->searchable(),
                Tables\Columns\TextColumn::make('first_mobile')
                    ->searchable(),
                Tables\Columns\TextColumn::make('region.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('town')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
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
            'index' => Pages\ManageProducts::route('/'),
            'view' => Pages\ViewProduct::route('/view/{record}')
        ];
    }

    public static function can(string $action, \Illuminate\Database\Eloquent\Model|null $record = null): bool
    {
        return true;
    }
}

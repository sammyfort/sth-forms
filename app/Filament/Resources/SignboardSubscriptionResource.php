<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SignboardSubscriptionResource\Pages;
use App\Filament\Resources\SignboardSubscriptionResource\RelationManagers;
use App\Models\SignboardSubscription;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SignboardSubscriptionResource extends Resource
{
    protected static ?string $model = SignboardSubscription::class;

    protected static ?string $navigationIcon = 'govicon-money';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('signboard_id')
                    ->relationship('signboard', 'id'),
                Forms\Components\Select::make('plan_id')
                    ->relationship('plan', 'name'),
                Forms\Components\TextInput::make('amount')
                    ->numeric(),
                Forms\Components\TextInput::make('payment_reference')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('payment_status')
                    ->required()
                    ->maxLength(255)
                    ->default('pending'),
                Forms\Components\TextInput::make('payment_channel')
                    ->maxLength(255),
                Forms\Components\DateTimePicker::make('starts_at'),
                Forms\Components\DateTimePicker::make('ends_at'),
                Forms\Components\TextInput::make('receipt_number')
                    ->maxLength(255),
                Forms\Components\TextInput::make('created_by_id')
                    ->numeric(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('signboard.id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('signboard.business.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('plan.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('amount')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('payment_status')
                    ->searchable(),
                Tables\Columns\TextColumn::make('starts_at')
                    ->label('Started Date')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('ends_at')
                    ->label('End Date')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
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
            'index' => Pages\ManageSignboardSubscriptions::route('/'),
            'view' => Pages\ViewSignboardSubscription::route('/{record}'),
        ];
    }
}

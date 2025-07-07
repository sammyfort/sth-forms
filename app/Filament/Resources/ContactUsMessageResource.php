<?php

namespace App\Filament\Resources;

use App\Enums\ContactUsMessageStatus;
use App\Filament\Resources\ContactUsMessageResource\Pages;
use App\Filament\Resources\ContactUsMessageResource\RelationManagers;
use App\Models\ContactUsMessage;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ContactUsMessageResource extends Resource
{
    protected static ?string $model = ContactUsMessage::class;

    protected static ?string $navigationIcon = 'elemplus-message';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make("name")
                    ->hiddenOn('edit')
                    ->label("Sender's Name"),
                Forms\Components\TextInput::make('mobile')
                    ->hiddenOn('edit'),
                Forms\Components\TextInput::make('email')
                    ->hiddenOn('edit'),
                Forms\Components\Textarea::make('message')
                    ->hiddenOn('edit'),
                Forms\Components\Select::make('status')
                    ->options(ContactUsMessageStatus::class)
                    ->required()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label("Sender's Name")
                    ->searchable(),
                Tables\Columns\TextColumn::make('mobile')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('status')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Sent On')
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
            'index' => Pages\ManageContactUsMessages::route('/'),
            'view' => Pages\ViewContactUsMessage::route('/{record}'),
        ];
    }
}

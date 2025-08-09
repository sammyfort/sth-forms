<?php

namespace App\Filament\Resources;

use App\Enums\JobStatus;
use App\Filament\Resources\JobResource\Pages;
use App\Filament\Resources\JobResource\RelationManagers;
use App\Models\Job;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class JobResource extends Resource
{
    protected static ?string $model = Job::class;

    protected static ?string $navigationIcon = 'eos-work';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('company_name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('title')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Textarea::make('summary')
                    ->required()
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('description')
                    ->required()
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('job_type')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('work_mode')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('region_id')
                    ->relationship('region', 'name'),
                Forms\Components\TextInput::make('town')
                    ->maxLength(255),
                Forms\Components\TextInput::make('salary')
                    ->maxLength(255),
                Forms\Components\Textarea::make('how_to_apply')
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('application_link')
                    ->maxLength(255),
                Forms\Components\DateTimePicker::make('deadline')
                    ->required(),
                Forms\Components\Select::make('Category')
                    ->relationship('categories', 'name')
                    ->searchable()
                    ->required(),
                Forms\Components\Select::make('status')
                    ->options([
                        'active' => 'Active',
                        'inactive' => 'Inactive',
                        'paused' => 'Paused',
                    ])
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.fullname')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('company_name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('title')
                    ->searchable(),
                Tables\Columns\TextColumn::make('job_type')
                    ->searchable(),
                Tables\Columns\TextColumn::make('work_mode')
                    ->searchable(),
                Tables\Columns\TextColumn::make('region.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('town')
                    ->searchable(),
                Tables\Columns\TextColumn::make('salary')
                    ->searchable(),
                Tables\Columns\TextColumn::make('deadline')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->searchable(),
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
            'index' => Pages\ManageJobs::route('/'),
            'view' => Pages\ViewJob::route('/view/{record}'),
        ];
    }

    public static function can(string $action, \Illuminate\Database\Eloquent\Model|null $record = null): bool
    {
        return true;
    }
}

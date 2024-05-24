<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProgramaResource\Pages;
use App\Filament\Resources\ProgramaResource\RelationManagers;
use App\Models\Programa;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
// use Illuminate\Support\HtmlString;

class ProgramaResource extends Resource
{
    protected static ?string $model = Programa::class;

    protected static ?string $navigationGroup = 'Gestion de Programas'; //para agregar un menu desplegable
    protected static ?string $navigationIcon = 'heroicon-s-square-3-stack-3d'; //editar iconos
    protected static ?int $navigationSort = 1; // organizar el menu de arriba hacia abajo

    public static function getNavigationBadge(): ?string  //Esto pondra un contador en el menu de Convenios dira la cantidad
    {
        return static::getModel()::count();
    }
    public static function getNavigationBadgeColor(): ?string
    {
        // Retorna el color que deseas asignar al badge de navegación
        return 'gray'; // Por ejemplo, un badge azul con texto blanco
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nombre')
                    ->label('Nombre del Programa') 
                    ->required()
                    ->unique(ignoreRecord: true) //que sea unico al crear y cuando se edite se ignore 
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                
                Tables\Columns\TextColumn::make('nombre')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                
            ]) 
            ->filters([
                //
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
            'index' => Pages\ListProgramas::route('/'),
            'create' => Pages\CreatePrograma::route('/create'),
            'edit' => Pages\EditPrograma::route('/{record}/edit'),
        ];
    }
}

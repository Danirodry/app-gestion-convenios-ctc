<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EstudianteResource\Pages;
use App\Filament\Resources\EstudianteResource\RelationManagers;
use App\Models\Estudiante;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Select;
use Filament\Tables\Filters\SelectFilter;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Section;
use Filament\Support\Enums\FontWeight;



class EstudianteResource extends Resource
{
    protected static ?string $model = Estudiante::class;

    protected static ?string $navigationGroup = 'Gestion Practica Empresarial'; //para agregar un menu desplegable
    protected static ?string $navigationIcon = 'heroicon-s-users'; //editar iconos
    protected static ?int $navigationSort = 1; // organizar el menu de arriba hacia abajo

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()
                    ->columns(3)
                    ->schema([
                        Forms\Components\TextInput::make('documento')
                            // ->prefix('C.C')
                            ->helperText('Ingresar Solamente Numeros, sin (.,)')
                            ->unique(ignoreRecord: true) //que sea unico al crear y cuando se edite se ignore 
                            ->numeric()
                            ->minLength(8)
                            ->maxLength(10)
                            ->placeholder('1234567890')
                            ->label('Documento')
                            ->required(),
                        Forms\Components\TextInput::make('nombre')
                            ->maxLength(255)
                            ->required(),
                        Forms\Components\TextInput::make('correo')
                            ->email()
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('tel_cel')
                            ->label('Tel / Cel')
                            ->tel()
                            ->minLength(10)
                            ->maxLength(10)
                            ->required(),
                        Forms\Components\TextInput::make('direccion')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\Select::make('programas_id') //hacer la busqueda por seleccion (use)
                            ->relationship('programas','nombre')//Se hace la relacion
                            ->required()
                            ->searchable()
                            ->preload(),
                    ]),
                    Section::make('Informacion Adicional')
                        ->columns(3)
                        ->schema([ 
                            Forms\Components\Select::make('estado_estudiante')
                                ->label('Estado del Estudiante')
                                ->options([
                                    'completado' => 'Completado',
                                    'por_completar' => 'Por Completar',
                                    'cancelado' => 'Cancelado',
                                    ])
                                    ->required(),
                            Forms\Components\Textarea::make('observaciones')
                                    ->autosize()
                                    ->columnSpan('full')
                                    ->maxLength(255),  
                        ]),
                   
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('documento')
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->weight(FontWeight::Bold)
                    ->searchable(),
                Tables\Columns\TextColumn::make('nombre')
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->searchable(), 
                Tables\Columns\TextColumn::make('correo')
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->searchable(),   
                Tables\Columns\TextColumn::make('tel_cel')
                    ->copyable()
                    ->copyMessage('Numero Copiado')
                    ->copyMessageDuration(1500)
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->label('Tel / Cel')
                    ->searchable(),
                Tables\Columns\TextColumn::make('direccion')
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->searchable(),
                Tables\Columns\TextColumn::make('programas.nombre') 
                    ->label('Programa')
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->searchable(),
                Tables\Columns\TextColumn::make('estado_estudiante')
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->badge()                                             //Los vuelve formato etiqueta
                    ->color(fn (string $state): string => match ($state){ //Da color
                        'completado' => 'success',
                        'por_completar' => 'warning',
                        'cancelado' => 'danger',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state){ //Reemplaza los nombres
                        'completado' => 'Completado',
                        'por_completar' => 'Por Completar',
                        'cancelado' => 'Cancelado',
                    })
                    ->searchable()
                    ->label('Estado'),
                Tables\Columns\TextColumn::make('observaciones')
                    ->wrap()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Fecha de creación')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('estado_estudiante') //integrar busqueta por filtro en los estados (use)
                    ->options([
                        'completado' => 'Completado',
                        'por_completar' => 'Por Completar',
                        'cancelado' => 'Cancelado',
                    ])
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),
                ])->tooltip('Acciones') ->color('indigo')->icon('heroicon-s-adjustments-horizontal'),

                
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
            'index' => Pages\ListEstudiantes::route('/'),
            'create' => Pages\CreateEstudiante::route('/create'),
            'view' => Pages\ViewEstudiante::route('/{record}'),
            'edit' => Pages\EditEstudiante::route('/{record}/edit'),
        ];
    }
}

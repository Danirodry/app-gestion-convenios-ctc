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
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;
use Guava\FilamentClusters\Forms\Cluster;
use Filament\Forms\Components\Fieldset;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components;
use Filament\Infolists\Components\Grid;

class EstudianteResource extends Resource
{
    protected static ?string $model = Estudiante::class;

    protected static ?string $navigationGroup = 'Gestion Practica Empresarial'; 
    protected static ?string $navigationIcon = 'heroicon-s-users'; 
    protected static ?int $navigationSort = 1; 

    public static function getNavigationBadge(): ?string 
    {
        return static::getModel()::count();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Fieldset::make('Datos del Estudiante')
                    ->schema([
                        Cluster::make([
                            Forms\Components\Select::make('tipo_documento')
                                ->label('Tipo de Documento')
                                ->placeholder('Tipo de Documento')
                                ->options([
                                    'CC' => 'CC - Cédula de Ciudadanía',
                                    'TI' => 'TI - Tarjeta de Identidad',
                                    'CE' => 'CE - Cédula Extranjera',
                                    'PEP' => 'PEP - Permiso Especial de Permanencia ',
                                    'PPT' => 'PPT - Permiso por Protección Temporal',
                                    ])
                                    ->suffixIcon('heroicon-m-identification')
                                    ->required(),
                            Forms\Components\TextInput::make('documento')
                                // ->prefix('C.C')
                                ->unique(ignoreRecord: true) //que sea unico al crear y cuando se edite se ignore 
                                ->numeric()
                                ->maxLength(10)
                                ->placeholder('1234567890')
                                ->required(),
                                    ])->label('Documento de identidad')
                                    // ->hint('Numero del Documento')
                                    ->helperText('Ingresar solamente Numeros sin puntos ni comas'),
                        
                        Forms\Components\TextInput::make('nombre')
                            ->label('Nombre Completo')
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
                            ->label('Dirección')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\Select::make('programas_id') //hacer la busqueda por seleccion (use)
                            ->relationship('programas','nombre')//Se hace la relacion
                            ->required()
                            ->searchable()
                            ->preload(),
                    ])->columns(2),
         
                    Fieldset::make('Infomación Adicional')
                        ->schema([
                            Forms\Components\Select::make('tipo_estudiante')
                                ->label('El Estudiante es:')
                                ->options([
                                    'interno' => 'Interno (CTC)',
                                    'externo' => 'Externo (Otra institución)',
                                    ])
                                    ->required(),
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
                    
                        ])->columns(3),        
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('tipo_documento')
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->searchable()
                    ->label('Tipo'),
                Tables\Columns\TextColumn::make('documento')
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->weight(FontWeight::Bold)    // use
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
                Tables\Columns\TextColumn::make('tipo_estudiante')
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->searchable()
                    ->label('Estudiante'),
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

            //integrar busqueta por filtro en los estados (use)

            ->filters([
                SelectFilter::make('estado_estudiante') 
                    ->options([
                        'completado' => 'Completado',
                        'por_completar' => 'Por Completar',
                        'cancelado' => 'Cancelado',
                    ]),
                SelectFilter::make('tipo_estudiante')
                    ->options([
                        'interno' => 'Interno (CTC)',
                        'externo' => 'Externo (Otra institución)',
                        ])
                          
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make()
                    ->label('Ver'),
                    Tables\Actions\EditAction::make()
                    ->color('warning'),
                    Tables\Actions\DeleteAction::make()
                    ->color('danger'),
                ])->tooltip('Acciones') ->color('indigo')->icon('heroicon-s-adjustments-horizontal'),

                
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    ExportBulkAction::make()
                ]),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
{
    return $infolist
        ->schema([
            Components\Section::make()->schema([
                components\Grid::make(4)->schema([
                    Components\TextEntry::make('tipo_documento')
                    ->label('Tipo de Documento')
                    ->weight(FontWeight::Bold),
                    Components\TextEntry::make('documento')
                    ->label('Numero de Documento')
                    ->weight(FontWeight::Bold),
                    Components\TextEntry::make('nombre')
                    ->label('Nombre')
                    ->weight(FontWeight::Bold),
                    Components\TextEntry::make('correo')
                    ->label('Correo electronico')
                    ->weight(FontWeight::Bold),
                    Components\TextEntry::make('tel_cel')
                    ->label('Telefono o Celular')
                    ->weight(FontWeight::Bold),
                    Components\TextEntry::make('tipo_estudiante')
                    ->label('Tipo de Estudiante')
                    ->weight(FontWeight::Bold),
                    Components\TextEntry::make('direccion')
                    ->label('Dirección de residencia')
                    ->weight(FontWeight::Bold),
                    Components\TextEntry::make('programas.nombre')
                    ->label('Nombre del programa academico')
                    ->weight(FontWeight::Bold),
                    Components\TextEntry::make('estado_estudiante')
                    ->label('Estado del Estudiante')
                    ->badge()                                             
                    ->color(fn (string $state): string => match ($state){ 
                        'completado' => 'success',
                        'por_completar' => 'warning',
                        'cancelado' => 'danger',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state){
                        'completado' => 'Completado',
                        'por_completar' => 'Por Completar',
                        'cancelado' => 'Cancelado',
                    }),
                    Components\TextEntry::make('created_at')
                        ->weight(FontWeight::Bold)
                        ->label('Fecha de creación')
                        ->date()
                    
                ]),
                Components\TextEntry::make('observaciones')
                ->weight(FontWeight::Bold),
                
            ])
            
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

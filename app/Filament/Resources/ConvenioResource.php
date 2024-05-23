<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ConvenioResource\Pages;
use App\Filament\Resources\ConvenioResource\RelationManagers;
use App\Models\Convenio;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Filters\SelectFilter;


class ConvenioResource extends Resource
{
    protected static ?string $model = Convenio::class;

    protected static ?string $navigationIcon = 'heroicon-s-rectangle-stack';
    protected static ?string $navigationGroup = 'Gestion Practica Empresarial'; //para agregar un menu desplegable
    protected static ?int $navigationSort = 1; // organizar el menu de arriba hacia abajo
    

    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                Section::make('Relación del convenio de la Empresa y del Estudiante')
                ->description('Si no aparece la Empresa o el Estudiante, debe Registrarlos en el icono +')
                ->icon('heroicon-c-document-duplicate') //Agregar icono al encabezado
                ->aside()   //Coloca el encabezado y la descripción a un lado
                ->schema([

                    Forms\Components\Select::make('empresas_id')
                        ->relationship('empresas','nit')//Se hace la relacion
                        ->required()
                        ->label('NIT de la Empresa')
                        ->searchable()
                        ->preload()
                            //Crer nueva Empresa en una ventana modal (sin salir de crear convenios)
                            ->createOptionForm([
                                Section::make('Crear datos de la empresa')
                                ->columns(3)
                                ->schema([
                                    Forms\Components\TextInput::make('nit')
                                        ->label('NIT')
                                        ->required(),
                                    Forms\Components\TextInput::make('n_convenio')
                                        ->label('N° Convenio')
                                        ->required(),
                                    Forms\Components\TextInput::make('nombre')
                                        ->required(),
                                    Forms\Components\TextInput::make('tel_cel')
                                        ->label('Tel / Cel')
                                        ->required(),
                                    Forms\Components\TextInput::make('direccion')
                                        ->required(),
                                    Forms\Components\TextInput::make('correo')
                                        ->required(),
                                    Forms\Components\TextInput::make('representante_legal')
                                        ->required(),
                                ]),
                                
                            Section::make('Informacion Adicional')
                                    
                                ->schema([ 
                                    Forms\Components\Select::make('estado_empresa')
                                        ->label('Estado de la Empresa')
                                        ->options([
                                            'completado' => 'Completado',
                                            'por_completar' => 'Por Completar',
                                            'cancelado' => 'Cancelado',
                                        ])
                                        ->required(),
                                    Forms\Components\Textarea::make('observaciones')
                                        ->autosize()
                                        ->columnSpan('full')
                                        ->required(), 
                                        ])
                                        ->columns(3),
                               
                        
                                            
                            Section::make('Duración del Convenio')
                                    
                                ->schema([       
                                    Forms\Components\DatePicker::make('inicio_convenio')
                                         ->placeholder('may 22, 2024')    
                                        ->native(false)
                                        ->required(),
                                    Forms\Components\DatePicker::make('fin_convenio')
                                        ->placeholder('jul 23, 2024')    
                                        ->native(false)
                                         ->required(),
                                         ])
                                         ->columns(3),
                            ]), 


                    Forms\Components\Select::make('estudiantes_id')
                        ->relationship('estudiantes','documento')//Se hace la relacion
                        ->required()
                        ->label('Documento del Estudiante')
                        ->searchable()
                        ->preload()
                            ->createOptionForm([

                                Section::make('Crear datos del Estudiante')
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

                                        ]),        
                                            
                                    ]),

                        Section::make('Informacion Adicional')
                        ->columns(3)
                        ->schema([ 
                            Forms\Components\Select::make('estado_convenio')
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
                
                        Section::make('Duración del convenio')
                        ->columns(4)
                        ->schema([
                            
                            Forms\Components\DatePicker::make('fecha_inicio')
                                ->native(false)
                                ->required(),
                            Forms\Components\DatePicker::make('fecha_fin')
                                ->native(false)
                                ->required(),
                        ]),
            ]);

    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id') 
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->label('ID')
                    ->searchable(),
                Tables\Columns\TextColumn::make('empresas.nit') 
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->label('NIT')
                    ->searchable(),
                Tables\Columns\TextColumn::make('empresas.nombre')
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->searchable(),
                Tables\Columns\TextColumn::make('estudiantes.documento')
                    ->toggleable(isToggledHiddenByDefault: false) 
                    ->label('Documento')
                    ->searchable(),
                Tables\Columns\TextColumn::make('estudiantes.nombre')
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->searchable(),
                Tables\Columns\TextColumn::make('estado_convenio')
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->badge()
                    ->color(fn (string $state): string => match ($state){
                        'completado' => 'success',
                        'por_completar' => 'warning',
                        'cancelado' => 'gray',
                    })
                    ->searchable()
                    ->label('Estado'),
                Tables\Columns\TextColumn::make('observaciones') 
                    ->wrap()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('fecha_inicio')
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->date()
                    ->searchable(),
                Tables\Columns\TextColumn::make('fecha_fin')
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->date()
                    ->searchable()
                    
                
            ])
            ->filters([
                SelectFilter::make('estado_convenio') //integrar busqueta por filtro en los estados (use)
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
            'index' => Pages\ListConvenios::route('/'),
            'create' => Pages\CreateConvenio::route('/create'),
            'view' => Pages\ViewConvenio::route('/{record}'),
            'edit' => Pages\EditConvenio::route('/{record}/edit'),
        ];
    }
}

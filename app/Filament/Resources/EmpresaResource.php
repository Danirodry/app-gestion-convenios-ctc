<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EmpresaResource\Pages;
use App\Filament\Resources\EmpresaResource\RelationManagers;
use App\Models\Empresa;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Section;
use Filament\Tables\Filters\SelectFilter;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;

class EmpresaResource extends Resource
{
    protected static ?string $model = Empresa::class;

    protected static ?string $navigationIcon = 'heroicon-s-building-office-2'; //editar iconos
    protected static ?string $navigationGroup = 'Gestion Practica Empresarial'; //para agregar un menu desplegable
    protected static ?int $navigationSort = 1; // organizar el menu de arriba hacia abajo

    public static function getNavigationBadge(): ?string  //Esto pondra un contador en el menu de Convenios dira la cantidad
    {
        return static::getModel()::count();
    }
    public static function getNavigationBadgeColor(): ?string //agregar color al contador
    {    
        return 'info'; 
    }

    public static function form(Form $form): Form
    {
        return $form
        ->schema([
            Section::make()
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
                ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nit')
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->label('NIT')
                    ->searchable(),
                Tables\Columns\TextColumn::make('n_convenio')
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->label('N° Convenio')
                    ->searchable(),
                Tables\Columns\TextColumn::make('nombre')
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->searchable(),
                Tables\Columns\TextColumn::make('tel_cel')
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->label('Tel / Cel')
                    ->searchable(),
                Tables\Columns\TextColumn::make('direccion')
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->searchable(),
                Tables\Columns\TextColumn::make('correo')
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->searchable(),
                Tables\Columns\TextColumn::make('representante_legal')
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->searchable(),
                Tables\Columns\TextColumn::make('estado_empresa')
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->label('Estado de de la Empresa')
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
                Tables\Columns\TextColumn::make('inicio_convenio')
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->date()
                    ->searchable(),
                Tables\Columns\TextColumn::make('fin_convenio')
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->date()
                    ->searchable(),
            ])
            ->filters([
                SelectFilter::make('estado_empresa') //integrar busqueta por filtro en los estados (use)
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
                    ExportBulkAction::make()
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
            'index' => Pages\ListEmpresas::route('/'),
            'create' => Pages\CreateEmpresa::route('/create'),
            'view' => Pages\ViewEmpresa::route('/{record}'),
            'edit' => Pages\EditEmpresa::route('/{record}/edit'),
        ];
    }
}

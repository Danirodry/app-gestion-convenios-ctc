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
use Filament\Support\Enums\FontWeight;
use Filament\Tables\Filters\SelectFilter;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;
use Filament\Forms\Components\Fieldset;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components;
use Filament\Infolists\Components\Grid;

class EmpresaResource extends Resource
{
    protected static ?string $model = Empresa::class;

    protected static ?string $navigationIcon = 'heroicon-s-building-office-2'; 
    protected static ?string $navigationGroup = 'Gestion Practica Empresarial'; 
    protected static ?int $navigationSort = 1; 

    public static function getNavigationBadge(): ?string  
    {
        return static::getModel()::count();
    }

    public static function getNavigationBadgeColor(): ?string
    {    
        return 'info'; 
    }

    public static function form(Form $form): Form
    {
        return $form
        ->schema([
            Fieldset::make('Datos de la Empresa')
                ->schema([
                    Forms\Components\TextInput::make('nit')
                        ->unique(ignoreRecord: true)
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
                ])->columns(3),

            Fieldset::make('Informacion Adicional')
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
                        
                        ])->columns(3),    
 
            Fieldset::make('Duración del Convenio')
                ->schema([
                    Forms\Components\DatePicker::make('inicio_convenio')
                        ->placeholder('may 22, 2024')    
                        ->native(false)
                        ->required(),
                    Forms\Components\DatePicker::make('fin_convenio')
                        ->placeholder('jul 23, 2024')    
                        ->native(false)
                         ->required(),      
                ])->columns(3),    
      
                    
                ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nit')
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->weight(FontWeight::Bold)
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

            //integrar busqueta por filtro en los estados (use)

            ->filters([
                SelectFilter::make('estado_empresa') 
                    ->options([
                        'completado' => 'Completado',
                        'por_completar' => 'Por Completar',
                        'cancelado' => 'Cancelado',
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
                    Components\TextEntry::make('nit')
                    ->label('NIT de la Empresa')
                    ->weight(FontWeight::Bold),
                    Components\TextEntry::make('n_convenio')
                    ->label('Numero del Convenio')
                    ->weight(FontWeight::Bold),
                    Components\TextEntry::make('nombre')
                    ->label('Nombre de la Empresa')
                    ->weight(FontWeight::Bold),
                    Components\TextEntry::make('tel_cel')
                    ->label('Telefono o Celular')
                    ->weight(FontWeight::Bold),
                    Components\TextEntry::make('direccion')
                    ->label('Dirección')
                    ->weight(FontWeight::Bold),
                    Components\TextEntry::make('correo')
                    ->label('Correo electronico')
                    ->weight(FontWeight::Bold),
                    Components\TextEntry::make('representante_legal')
                    ->label('Representante legal')
                    ->weight(FontWeight::Bold),
                    Components\TextEntry::make('estado_empresa')
                    ->label('Estado de la Empresa')
                    ->badge()
                    ->color(fn (string $state): string => match ($state){
                        'completado' => 'success',
                        'por_completar' => 'warning',
                        'cancelado' => 'gray',
                    })
                    ->weight(FontWeight::Bold),
                    Components\TextEntry::make('inicio_convenio')
                    ->label('Inicio del convenio con la empresa')
                    ->date()
                    ->weight(FontWeight::Bold),
                    Components\TextEntry::make('fin_convenio')
                    ->label('Fin del convenio con la empresa')
                    ->date()
                    ->weight(FontWeight::Bold),
                    
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
            'index' => Pages\ListEmpresas::route('/'),
            'create' => Pages\CreateEmpresa::route('/create'),
            'view' => Pages\ViewEmpresa::route('/{record}'),
            'edit' => Pages\EditEmpresa::route('/{record}/edit'),
        ];
    }
}

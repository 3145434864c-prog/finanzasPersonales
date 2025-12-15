<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AhorroResource\Pages;
use App\Filament\Resources\AhorroResource\RelationManagers;
use App\Models\Ahorro;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Carbon\Carbon;
use Filament\Notifications\Notification;

class AhorroResource extends Resource
{
    protected static ?string $model = Ahorro::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Información de la Meta de Ahorro')
                    ->schema([
                        Forms\Components\Hidden::make('tipo_ahorro')
                            ->default('meta'),
                        Forms\Components\TextInput::make('nombre_meta')
                            ->label('Nombre de la Meta')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('monto_objetivo')
                            ->label('Monto Objetivo')
                            ->numeric()
                            ->required()
                            ->prefix('$')
                            ->minValue(0)
                            ->reactive()
                            ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                self::calculateFechaObjetivo($set, $get);
                            }),
                        Forms\Components\RichEditor::make('descripcion')
                            ->label('Descripción')
                            ->maxLength(65535)
                            ->columnSpanFull(),
                    ])->columns(2),

                Forms\Components\Section::make('Configuración de Aportes')
                    ->schema([
                        Forms\Components\Select::make('periodicidad')
                            ->label('Periodicidad de Aportes')
                            ->options([
                                'diario' => 'Diario',
                                'semanal' => 'Semanal',
                                'mensual' => 'Mensual',
                            ])
                            ->required()
                            ->reactive()
                            ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                self::calculateFechaObjetivo($set, $get);
                            }),
                        Forms\Components\TextInput::make('monto_aporte')
                            ->label('Monto por Aporte')
                            ->numeric()
                            ->required()
                            ->prefix('$')
                            ->minValue(0)
                            ->reactive()
                            ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                self::calculateFechaObjetivo($set, $get);
                            }),
                        Forms\Components\DatePicker::make('fecha_inicio')
                            ->label('Fecha de Inicio')
                            ->required()
                            ->default(now())
                            ->reactive()
                            ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                self::calculateFechaObjetivo($set, $get);
                            }),
                        Forms\Components\Hidden::make('fecha_objetivo'),
                    ])->columns(2),


            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nombre_meta')
                    ->label('Meta de Ahorro')
                    ->searchable(),
                Tables\Columns\TextColumn::make('monto_objetivo')
                    ->label('Monto Objetivo')
                    ->money('COP')
                    ->sortable(),
                Tables\Columns\TextColumn::make('monto_ahorrado')
                    ->label('Monto Ahorrado')
                    ->money('COP')
                    ->sortable(),
                Tables\Columns\TextColumn::make('periodicidad')
                    ->label('Periodicidad'),
                Tables\Columns\TextColumn::make('monto_aporte')
                    ->label('Aporte')
                    ->money('COP'),
                Tables\Columns\TextColumn::make('fecha_objetivo')
                    ->label('Fecha Objetivo')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('estado')
                    ->label('Estado')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'activo' => 'success',
                        'completado' => 'gray',
                        'cancelado' => 'danger',
                        default => 'gray',
                    }),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('estado')
                    ->options([
                        'activo' => 'Activo',
                        'completado' => 'Completado',
                        'cancelado' => 'Cancelado',
                    ]),
                Tables\Filters\SelectFilter::make('periodicidad')
                    ->options([
                        'diario' => 'Diario',
                        'semanal' => 'Semanal',
                        'mensual' => 'Mensual',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->button()
                    ->color('success'),
                Tables\Actions\DeleteAction::make()
                    ->button()
                    ->color('danger')
                    ->successNotification(
                        Notification::make()
                            ->title('Ahorro Eliminado')
                            ->body('El ahorro se eliminó exitosamente')
                            ->success()
                    ),
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
            'index' => Pages\ListAhorros::route('/'),
            'create' => Pages\CreateAhorro::route('/create'),
            'edit' => Pages\EditAhorro::route('/{record}/edit'),
        ];
    }

    protected static function calculateFechaObjetivo(callable $set, callable $get): void
    {
        $montoObjetivo = $get('monto_objetivo');
        $montoAporte = $get('monto_aporte');
        $periodicidad = $get('periodicidad');
        $fechaInicio = $get('fecha_inicio');

        if (!$montoObjetivo || !$montoAporte || !$periodicidad || !$fechaInicio) {
            return;
        }

        $numeroAportes = ceil($montoObjetivo / $montoAporte);

        $fechaInicioCarbon = Carbon::parse($fechaInicio);

        switch ($periodicidad) {
            case 'diario':
                $fechaObjetivo = $fechaInicioCarbon->addDays($numeroAportes - 1);
                break;
            case 'semanal':
                $fechaObjetivo = $fechaInicioCarbon->addWeeks($numeroAportes - 1);
                break;
            case 'mensual':
                $fechaObjetivo = $fechaInicioCarbon->addMonths($numeroAportes - 1);
                break;
            default:
                return;
        }

        $set('fecha_objetivo', $fechaObjetivo->format('Y-m-d'));
    }
}

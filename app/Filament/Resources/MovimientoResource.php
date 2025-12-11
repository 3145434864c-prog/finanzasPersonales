<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MovimientoResource\Pages;
use App\Filament\Resources\MovimientoResource\RelationManagers;
use App\Models\Movimiento;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Models\User;
use App\Models\Categoria;
use Filament\Tables\Filters\SelectFilter;
use Filament\Notifications\Notification;
use Filament\Forms\Components\Card;

class MovimientoResource extends Resource
{
    protected static ?string $model = Movimiento::class;

    protected static ?string $navigationIcon = 'heroicon-o-currency-dollar';

    public static function form(Form $form): Form
    {
        return $form
        ->schema([
            Card::make('LLene los campos del formulario')
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->label('Usuarios')
                    ->required()
                    ->options(User::all()->pluck('name', 'id')),
                Forms\Components\Select::make('categoria_id')
                    ->required()
                    ->label('Categorias')
                    ->options(Categoria::all()->pluck('nombre', 'id')),
                Forms\Components\Select::make('tipo')
                    ->options([
                        'ingreso' => 'Ingreso',
                        'gasto' => 'Gasto',])
                    ->required(),
                Forms\Components\TextInput::make('monto')
                    ->label('Monto')
                    ->required()
                    ->numeric(),
                Forms\Components\RichEditor::make('descripcion')
                    ->label('Descripcion')
                    ->required()
                    ->columnSpanFull(),
                Forms\Components\FileUpload::make('foto')
                    ->label('Foto')
                    ->image()
                    ->disk('public')
                    ->directory('movimientos'),
                Forms\Components\DatePicker::make('fecha')
                    ->required(),
            
                    ])
                    ->columns(2),
                ]);
            
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->sortable()
                    ->label('nro')
                    ->rowIndex(),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Usuario')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('categoria.nombre')
                    ->label('Categoria')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('tipo')
                    ->label('Tipo de movimiento')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('monto')
                    ->numeric()
                    ->sortable(),

                Tables\Columns\TextColumn::make('descripcion')
                    ->limit(50)
                    ->html()
                    ->searchable()
                    ->sortable(),

                Tables\Columns\ImageColumn::make('foto')
                    ->searchable()
                    ->width(100)
                    ->height(100),
             
                Tables\Columns\TextColumn::make('fecha')
                    ->date()
                    ->sortable(),
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
                    SelectFilter::make('tipo')
                        ->options([
                            'ingreso' => 'Ingreso',
                            'gasto'   => 'Gasto',
                        ])
                        ->placeholder('Filtrar por tipo ')
                        ->label('Tipo'),
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
                        ->title('Movimiento Eliminado')
                        ->body('El movimiento se elimino exitosamente')
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
            'index' => Pages\ListMovimientos::route('/'),
            'create' => Pages\CreateMovimiento::route('/create'),
            'edit' => Pages\EditMovimiento::route('/{record}/edit'),
        ];
    }
}

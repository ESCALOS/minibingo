<?php

namespace App\Livewire;

use App\Models\Bingo;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Livewire\Component;

class ListBingos extends Component implements HasForms, HasTable
{
    use InteractsWithForms;
    use InteractsWithTable;

    public function table(Table $table): Table
    {
        return $table
            ->query(Bingo::query())
            ->columns([
                TextColumn::make('title')
                    ->label('Título')
                    ->searchable(),
                TextColumn::make('elements')
                    ->label('Elementos')
                    ->badge()
                    ->separator(','),
                TextColumn::make('cols')
                    ->formatStateUsing(fn (Bingo $record) => $record->cols.'x'.$record->rows)
                    ->tooltip('(Vertical)x(Horizontal)'),
            ])
            ->headerActions([
                CreateAction::make('create')
                    ->model(Bingo::class)
                    ->label('Nuevo Bingo')
                    ->modalSubmitActionLabel('Guardar')
                    ->modalHeading('Nuevo Bingo')
                    ->form([
                        TextInput::make('title')
                            ->label('Título'),
                        TagsInput::make('elements')
                            ->label('Elementos')
                            ->placeholder('Nuevo elemento')
                            ->splitKeys(['Tab', ' '])
                            ->separator(',')
                            ->suggestions(['Papá', 'Mamá']),
                        Grid::make(['default' => 2])
                            ->schema([
                                TextInput::make('rows')
                                    ->label('Filas')
                                    ->helperText('Vertical')
                                    ->prefixIcon('heroicon-m-ellipsis-vertical')
                                    ->numeric()
                                    ->integer()
                                    ->minValue(1)
                                    ->maxValue(5),
                                TextInput::make('cols')
                                    ->label('Columnas')
                                    ->helperText('Horizontal')
                                    ->prefixIcon('heroicon-m-ellipsis-horizontal')
                                    ->numeric()
                                    ->integer()
                                    ->minValue(1)
                                    ->maxValue(5),
                            ]),
                    ]),
            ])
            ->filters([
                // ...
            ])
            ->actions([
                EditAction::make('edit')
                    ->form([
                        TextInput::make('title')
                            ->label('Título'),
                        TagsInput::make('elements')
                            ->label('Elementos')
                            ->placeholder('Nuevo elemento')
                            ->splitKeys(['Tab', ' '])
                            ->separator(',')
                            ->suggestions(['Papá', 'Mamá']),
                        Grid::make(['default' => 2])
                            ->schema([
                                TextInput::make('rows')
                                    ->label('Filas')
                                    ->helperText('Vertical')
                                    ->prefixIcon('heroicon-m-ellipsis-vertical')
                                    ->numeric()
                                    ->integer()
                                    ->minValue(1)
                                    ->maxValue(5),
                                TextInput::make('cols')
                                    ->label('Columnas')
                                    ->helperText('Horizontal')
                                    ->prefixIcon('heroicon-m-ellipsis-horizontal')
                                    ->numeric()
                                    ->integer()
                                    ->minValue(1)
                                    ->maxValue(5),
                            ]),
                    ]),
                DeleteAction::make('delete'),
            ])
            ->bulkActions([
                // ...
            ]);
    }

    public function render()
    {
        return view('livewire.list-bingos');
    }
}

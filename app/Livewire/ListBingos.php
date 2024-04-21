<?php

namespace App\Livewire;

use App\Models\Bingo;
use Barryvdh\DomPDF\Facade\Pdf;
use Closure;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Tables\Actions\Action;
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
                TextColumn::make('quantity')
                    ->label('Cantidad'),
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
                        Grid::make(['default' => 2])
                            ->schema([
                                TextInput::make('title')
                                    ->label('Título')
                                    ->required(),
                                TextInput::make('quantity')
                                    ->label('Cantidad')
                                    ->numeric()
                                    ->integer()
                                    ->rules([
                                        fn (Get $get): Closure => function (string $attribute, $value, Closure $fail) use ($get) {
                                            if ($value < $get('total')) {
                                                $fail('La cantidad debe ser mayor o igual al total de elementos');
                                            }
                                        },
                                    ])
                                    ->minValue(fn (Get $get) => $get('total'))
                                    ->required(),
                                TagsInput::make('elements')
                                    ->label('Elementos')
                                    ->placeholder('Nuevo elemento')
                                    ->splitKeys(['Tab', ' '])
                                    ->separator(',')
                                    ->suggestions(['Papá', 'Mamá'])
                                    ->columnSpan(2)
                                    ->required()
                                    ->rules([
                                        fn (Get $get): Closure => function (string $attribute, $value, Closure $fail) use ($get) {
                                            $elements = count($value);
                                            $elementsByCard = $get('total');
                                            $totalCombinaciones = $this->combinatoria($elements, $elementsByCard);

                                            if ($totalCombinaciones === 0) {
                                                $combinacionesEsperadas = 0;
                                                while ($combinacionesEsperadas == 0) {
                                                    $elements++;
                                                    $combinacionesEsperadas = $this->combinatoria($elements, $elementsByCard);
                                                }
                                                $fail('Se necesitan mínimo: '.$elements);
                                            }

                                            if ($totalCombinaciones < $get('quantity')) {
                                                $fail('La cantidad máxima de cartillas es '.$totalCombinaciones.'. Agrega más elementos');
                                            }
                                        },
                                    ]),
                            ]),
                        Grid::make(['default' => 3])
                            ->schema([
                                TextInput::make('rows')
                                    ->label('Filas')
                                    ->helperText('Vertical')
                                    ->prefixIcon('heroicon-m-adjustments-vertical')
                                    ->numeric()
                                    ->integer()
                                    ->minValue(1)
                                    ->maxValue(5)
                                    ->required()
                                    ->afterStateUpdated(fn (Get $get, Set $set) => $set('total', $get('rows') * $get('cols')))
                                    ->live(debounce: 250),
                                TextInput::make('cols')
                                    ->label('Columnas')
                                    ->helperText('Horizontal')
                                    ->prefixIcon('heroicon-m-adjustments-horizontal')
                                    ->numeric()
                                    ->integer()
                                    ->minValue(1)
                                    ->maxValue(5)
                                    ->required()
                                    ->afterStateUpdated(fn (Get $get, Set $set) => $set('total', $get('rows') * $get('cols')))
                                    ->live(debounce: 250),
                                TextInput::make('total')
                                    ->label('Total')
                                    ->disabled(),
                            ]),
                    ]),
            ])
            ->filters([
                // ...
            ])
            ->actions([
                EditAction::make('edit')
                    ->form([
                        Grid::make(['default' => 2])
                            ->schema([
                                TextInput::make('title')
                                    ->label('Título')
                                    ->required(),
                                TextInput::make('quantity')
                                    ->label('Cantidad')
                                    ->numeric()
                                    ->integer()
                                    ->rules([
                                        fn (Get $get): Closure => function (string $attribute, $value, Closure $fail) use ($get) {
                                            if ($value < $get('total')) {
                                                $fail('La cantidad debe ser mayor o igual al total de elementos');
                                            }
                                        },
                                    ])
                                    ->minValue(fn (Get $get) => $get('total'))
                                    ->required()
                                    ->live(),
                                TagsInput::make('elements')
                                    ->label('Elementos')
                                    ->placeholder('Nuevo elemento')
                                    ->splitKeys(['Tab', ' '])
                                    ->separator(',')
                                    ->suggestions(['Papá', 'Mamá'])
                                    ->columnSpan(2)
                                    ->required()
                                    ->rules([
                                        fn (Get $get): Closure => function (string $attribute, $value, Closure $fail) use ($get) {
                                            $elements = count($value);
                                            $elementsByCard = $get('total');
                                            $totalCombinaciones = $this->combinatoria($elements, $elementsByCard);

                                            if ($totalCombinaciones === 0) {
                                                $combinacionesEsperadas = 0;
                                                while ($combinacionesEsperadas == 0) {
                                                    $elements++;
                                                    $combinacionesEsperadas = $this->combinatoria($elements, $elementsByCard);
                                                }
                                                $fail('Se necesitan mínimo: '.$elements);
                                            }

                                            if ($totalCombinaciones < $get('quantity')) {
                                                $fail('La cantidad máxima de cartillas es '.$totalCombinaciones.'. Agrega más elementos');
                                            }
                                        },
                                    ]),
                            ]),
                        Grid::make(['default' => 3])
                            ->schema([
                                TextInput::make('rows')
                                    ->label('Filas')
                                    ->helperText('Vertical')
                                    ->prefixIcon('heroicon-m-adjustments-vertical')
                                    ->numeric()
                                    ->integer()
                                    ->minValue(1)
                                    ->maxValue(5)
                                    ->required()
                                    ->afterStateUpdated(fn (Get $get, Set $set) => $set('total', $get('rows') * $get('cols')))
                                    ->live(debounce: 250),
                                TextInput::make('cols')
                                    ->label('Columnas')
                                    ->helperText('Horizontal')
                                    ->prefixIcon('heroicon-m-adjustments-horizontal')
                                    ->numeric()
                                    ->integer()
                                    ->minValue(1)
                                    ->maxValue(5)
                                    ->required()
                                    ->afterStateUpdated(fn (Get $get, Set $set) => $set('total', $get('rows') * $get('cols')))
                                    ->live(debounce: 250),
                                TextInput::make('total')
                                    ->label('Total')
                                    ->formatStateUsing(fn (Bingo $record) => $record->rows * $record->cols)
                                    ->disabled(),
                            ]),
                    ]),
                DeleteAction::make('delete'),
                Action::make('download')
                    ->label('Descargar')
                    ->icon('heroicon-m-arrow-down')
                    ->color('info')
                    ->action(function (Bingo $record) {
                        $pdf = Pdf::loadView('pdf.bingo', $record->toArray());

                        return response()->streamDownload(function () use ($pdf) {
                            echo $pdf->stream();
                        }, 'bingo.pdf');
                    }),
            ])
            ->bulkActions([
                // ...
            ]);
    }

    private function combinatoria($n, $k)
    {
        // Verificar si k es mayor que n o k es negativo
        if ($k < 0 || $k > $n) {
            return 0; // La combinatoria no está definida
        }

        // Calcular factoriales
        $factorial_k = 1;
        for ($i = $k + 1; $i <= $n; $i++) {
            $factorial_k *= $i;
        }

        // Calcular combinatoria
        $combinatoria = $factorial_k / $this->factorial($n - $k);

        return $combinatoria;
    }

    // Función para calcular factorial (puedes usar una función propia o la proporcionada anteriormente)
    private function factorial($n)
    {
        if ($n <= 1) {
            return 1;
        } else {
            return $n * $this->factorial($n - 1);
        }
    }

    public function render()
    {
        return view('livewire.list-bingos');
    }
}

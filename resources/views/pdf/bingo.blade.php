<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Bingo</title>
    <style>
        html {
            padding: 0;
            box-sizing: border-box;
        }

        .page-break {
            page-break-after: always;
        }

        .container {
            margin-top: 1.5rem;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            border: 1px solid black;
        }

        td {
            width: 20%;
            text-align: center;
        }

        #header td {
            font-size: 2.5rem;
            font-weight: bold;
            background-color: #ccc;
            padding: .5rem 0;
        }

        #content td {
            padding: 2rem .0rem;
            font-size: 2rem;
            border: 1px solid black;
        }
    </style>
</head>

<body>
    @php
        // Obtener todas las combinaciones posibles de cartillas
        $elementos = explode(',', $elements);
        $combinaciones_cartillas = generarCombinaciones($elementos, $cols, $rows, 10000);

        // Función para generar todas las combinaciones posibles de cartillas
        function generarCombinaciones($elementos, $cols, $rows, $limite)
        {
            // Inicializar array para almacenar las combinaciones
            $combinaciones = [];

            // Contador para rastrear el número de combinaciones generadas
            $contador = 0;

            // Generar todas las combinaciones posibles
            foreach (combinations($elementos, $cols * $rows) as $combinacion) {
                // Verificar si la combinación no tiene elementos repetidos
                if (count(array_unique($combinacion)) == count($combinacion)) {
                    // Agregar la combinación al array de combinaciones
                    $combinaciones[] = $combinacion;
                    $contador++;
                }

                if ($contador >= $limite) {
                    break;
                }
            }

            return $combinaciones;
        }

        // Función para generar todas las combinaciones posibles de un array
        function combinations($array, $k)
        {
            if ($k == 0) {
                return [[]];
            }

            if (count($array) == 0) {
                return [];
            }

            $element = array_shift($array);
            $sub_combinations = combinations($array, $k - 1);
            $combinations = [];
            foreach ($sub_combinations as $sub_combination) {
                $combinations[] = array_merge([$element], $sub_combination);
            }

            return array_merge($combinations, combinations($array, $k));
        }

        function getCardsByPage($rows): int
        {
            switch ($rows) {
                case 1:
                    return 5;
                case 2:
                    return 3;
                case 3:
                case 4:
                    return 2;
                default:
                    return 1;
            }
        }
    @endphp

    @for ($cartillas = 1; $cartillas <= $quantity; $cartillas++)
        @php
            // Obtener un índice aleatorio dentro del rango del array de combinaciones
            $indice_aleatorio = array_rand($combinaciones_cartillas);

            // Obtener la combinación correspondiente al índice aleatorio
            $combinacion = $combinaciones_cartillas[$indice_aleatorio];

            // Eliminar la combinación seleccionada del array
            array_splice($combinaciones_cartillas, $indice_aleatorio, 1);
        @endphp
        <div class="container">
            <table>
                <tr id="header">
                    <td>B</td>
                    <td>I</td>
                    <td>N</td>
                    <td>G</td>
                    <td>O</td>
                </tr>
            </table>
            <table id="content" style="border-top: none;">
                @for ($i = 0; $i < $rows; $i++)
                    <tr>
                        @for ($j = 0; $j < $cols; $j++)
                            <td>{{ $combinacion[$i * $cols + $j] }}</td>
                        @endfor
                    </tr>
                @endfor
            </table>
        </div>

        {{-- Salto de página si es necesario --}}
        @if ($cartillas % getCardsByPage($rows) === 0 && $cartillas !== $quantity)
            <div class="page-break"></div>
        @endif
    @endfor
</body>

</html>

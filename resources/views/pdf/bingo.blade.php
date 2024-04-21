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
            margin-top: 4rem;
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
            font-size: 2rem;
            font-weight: bold;
            background-color: #ccc;
            padding: .5rem 0;
        }

        #content td {
            padding: 2rem .0rem;
            font-size: 1.5rem;
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
    @endphp

    @for ($cartillas = 0; $cartillas < $quantity; $cartillas++)
        @php
            // Obtener una combinación aleatoria de las combinaciones posibles
            $combinacion = $combinaciones_cartillas[array_rand($combinaciones_cartillas)];
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
                @for ($i = 0; $i < $cols; $i++)
                    <tr>
                        @for ($j = 0; $j < $rows; $j++)
                            <td>{{ $combinacion[$i * $rows + $j] }}</td>
                        @endfor
                    </tr>
                @endfor
            </table>
        </div>

        @if ($cartillas % 2 !== 0 && $cartillas !== $quantity - 1)
            <div class="page-break"></div>
        @endif
    @endfor
</body>

</html>

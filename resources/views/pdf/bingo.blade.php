<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Bingo</title>
    <style>
        html {
            margin: 2rem;
            padding: 0;
            box-sizing: border-box;
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
        $elementos = $array = explode(',', $elements);
        shuffle($elementos);
    @endphp
    <div>
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
            <tr>
                <td>{{ $elementos[0] }}</td>
                <td>{{ $elementos[1] }}</td>
                <td>{{ $elementos[2] }}</td>
            </tr>
            <tr>
                <td>{{ $elementos[0] }}</td>
                <td>{{ $elementos[3] }}</td>
                <td>{{ $elementos[4] }}</td>
            </tr>
        </table>
    </div>

    <div style="margin-top: 2rem">
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
            @
            <tr>
                <td>Mamá</td>
                <td>1</td>
                <td>2</td>
                <td>Papá</td>
                <td>4</td>
            </tr>
            <tr>
                <td>5</td>
                <td>Memo</td>
                <td>7</td>
                <td>8</td>
                <td>9</td>
            </tr>
            <tr>
                <td>5</td>
                <td>Memo</td>
                <td>7</td>
                <td>8</td>
                <td>9</td>
            </tr>
        </table>
    </div>
</body>

</html>

<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        @include("header")
        <title>Laravel</title>
    </head>
    <body>
    <div>
    <table border="1">
        <tr>
            　<td>address</td>
            　<td>zip_code</td>
        </tr>
        @for ($i = 0; $i < count($data); $i++)
            <tr>
                　<td>{{$data[$i]->address}}</td>
                　<td>{{$data[$i]->zip_code}}</td>
            </tr>
        @endfor

    </table>
        　<td><button onclick="CreateLocation()">新增地址</button></td>
    </div>
    <div hidden id="lo">
        <form action="location" method="post">
        Address:<input type="text" name="address" required><br>
        Zip_Code:<input type="text" name="zip_code" required><br>
            <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
            <input type="submit" value="送出">
        </form>
    </div>
    </body>
    <script>
        function CreateLocation() {
            $("#lo").toggle();
        }
    </script>

</html>

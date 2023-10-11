<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <!-- Fonts -->

        <!-- Styles -->
    </head>
    <body class="antialiased">
        <form action={{ route('upload') }} method="POST" enctype="multipart/form-data">
            @csrf
            <input name="file" type="file"/>
            <button>Submit</button>
        </form>
    </body>
</html>

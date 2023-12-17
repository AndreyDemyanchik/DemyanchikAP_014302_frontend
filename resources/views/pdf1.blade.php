<!doctype html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    <div style="background: white">
        <h2 style="text-align: center">{{ $title }}</h2>

        <h3>Данные визуализации: </h3>

        @foreach($data as $datum)
            @if(isset($datum['chartName']))
                <h4>{{ $datum['chartName'] }}</h4>

                <ul>
                    @foreach(array_slice($datum, 1) as $key => $item)
                        <li>
                            {{ $key }} : {{ $item }}
                        </li>
                    @endforeach
                </ul>
            @else
                <h5>{{ $datum }}</h5>
            @endif
        @endforeach

        <h4>Комментарии к отчёту: </h4>
        {!! $comments !!}
    </div>
</body>
</html>

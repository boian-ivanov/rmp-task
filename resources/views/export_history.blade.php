<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Welcome to RMP</title>
    <link href="/css/app2.css" rel="stylesheet">
</head>

<body>
<div style='margin: 10px; text-align: center;'>
    <div class="header">History</div>
    @if (count($exports) > 0)
        <div class="all-exports">
            @foreach ($exports as $export)
                <div class="">
                    <a href="{{ $export['link'] }}">
                        {{ $export['name'] }}
                    </a>
                </div>
            @endforeach
        </div>
    @else
        <div class="">There were no previous exports.</div>
    @endif
</div>
</body>
</html>
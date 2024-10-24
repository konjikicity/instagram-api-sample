<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>YouTube Apiテスト</title>
    <style>
        .text-red {
            color: red;
        }

        .img-content img {
            width: 300px;
            height: 200px;
        }
    </style>
</head>

<body>
    @if (isset($error))
        <p class="text-red">{{ $error }}</p>
    @endif
    @foreach ($youtubeInfo as $info)
        <div class="img-content">
            <a href="{{ $info['video_url'] }}" target="_blank">
                <img src="{{ $info['thumbnail'] }}" />
            </a>
            <p>{{ $info['title'] }}</p>
            <p>{{ $info['published_at'] }}</p>
        </div>
    @endforeach
</body>

</html>

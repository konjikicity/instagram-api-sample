<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Instagram APIテスト</title>
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
    <form method="POST" action="{{ route('top.fetch') }}">
        @csrf
        <label>インスタグラムID</label>
        <input name="bussiness-id" type="text" required />
        <button type="submit">送信</button>
    </form>
    @if (isset($error))
        <p class="text-red">{{ $error }}</p>
    @endif
    @foreach ($instagramInfo as $info)
        <div class="img-content">
            <img src="{{ $info['media_url'] }}" />
            <p>{{ $info['time_stamp'] }}</p>
        </div>
    @endforeach
</body>

</html>

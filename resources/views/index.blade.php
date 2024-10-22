<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Instagram APIテスト</title>
    <style>
        .text-red {
            color: red;
        }
    </style>
</head>

<body>
    <form method="POST" action="{{ route('top.fetch') }}">
        @csrf
        <label>インスタグラムID</label>
        <input name="bussiness-id" type="text" />
        @if (isset($error))
            <p class="text-red">※ ビジネスアカウントのIDを入力してください</p>
        @endif
        <button type="submit">送信</button>
    </form>
</body>

</html>

<!DOCTYPE html>
<html>
<head><title>ログイン</title></head>
<body>
    <h1>ログイン</h1>

    <form method="post" action="/login/authenticate">

        <label for="email">メールアドレス</label>
        <input type="text" id="email" name="email" value=""><br>

        <label for="password">パスワード</label>
        <input type="password" id="password" name="password" value=""><br>

        <input type="submit" value="登録">
    </form>

</body>
</html>
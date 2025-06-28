<!DOCTYPE html>
<html>
<head><title>ユーザ登録</title></head>
<body>
    <h1>ユーザ登録</h1>

    <form method="post" action="/register/store">

        <label for="name">ユーザ名</label>
        <input type="text" id="name" name="name" value=""><br>

        <label for="email">メールアドレス</label>
        <input type="text" id="email" name="email" value=""><br>

        <label for="password">パスワード</label>
        <input type="password" id="password" name="password" value=""><br>

        <input type="submit" value="登録">
    </form>

</body>
</html>
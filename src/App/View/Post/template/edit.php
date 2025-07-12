<?php use App\Util\HtmlUtil; ?>

<!DOCTYPE html>
<html>
<head><title>投稿登録</title></head>
<body>
    <h1>投稿登録・編集</h1>

    <form method="post" action="/posts/save">
        <?php if (!empty($errors)): ?>
            <div style="color: red;">
                <?php foreach ($errors as $error): ?>
                    <p><?php echo HtmlUtil::escape($error) ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <input type="hidden" name="id" value="<?php echo HtmlUtil::escape(isset($post) ? $post->getId() : "") ?>">
        <label for="id">ID</label>
        <?php echo HtmlUtil::escape(isset($post) ? $post->getId() : "") ?><br>

        <label for="title">タイトル</label>
        <input type="text" id="title" name="title" value="<?php echo HtmlUtil::escape(isset($post) ? $post->getTitle() : "") ?>"><br>

        <label for="content">内容:</label>
        <textarea id="content" name="content"><?php echo HtmlUtil::escape(isset($post) ? $post->getContent() : "") ?></textarea><br>

        <input type="submit" value="登録">
    </form>

</body>
</html>
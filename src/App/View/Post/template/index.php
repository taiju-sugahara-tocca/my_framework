<?php use App\Util\HtmlUtil; ?>

<!DOCTYPE html>
<html>
<head><title>投稿一覧</title></head>
<body>
    <?php if (!empty($messages)): ?>
            <div style="width: 100% ; background-color: #f0f0f0; padding: 5px; border: 1px solid #ccc; margin-bottom: 20px;">
                <?php foreach ($messages as $message): ?>
                    <p><?php echo HtmlUtil::escape($message["text"]) ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

    <h1>投稿一覧</h1>
    <p>ユーザ名：<?php echo HtmlUtil::escape($user->getName())?></p>
    <p><a href="/logout">ログアウト</a><p>
    <p><a href="/posts/create">新規投稿</a><p>
    <hr>

    <form method="get" action="/posts">
        <h2>検索</h2>
        <label for="title">Title:</label>
        <input type="text" id="title" name="title" value="<?php echo HtmlUtil::escape($request->get('title')) ?>">
        <br>
        <label for="title">Content:</label>
        <input type="text" id="content" name="content" value="<?php echo HtmlUtil::escape($request->get('content')) ?>">
        <br>
        <label for="offset">取得開始地点:</label>
        <input type="text" id="offset" name="offset" value="<?php echo HtmlUtil::escape($request->get('offset')) ?>">
        <br>
        <label for="limit">取得件数:</label>
        <input type="text" id="limit" name="limit" value="<?php echo HtmlUtil::escape($request->get('limit')) ?>">
        <br>
        <label for="sort_column">ソート列:</label>
        <select id="sort_column" name="sort_column">
            <option value="">選択してください</option>
            <option value="id" <?php echo (HtmlUtil::escape($request->get('sort_column')) === 'id') ? 'selected' : '' ?>>ID</option>
            <option value="title" <?php echo (HtmlUtil::escape($request->get('sort_column')) === 'title') ? 'selected' : '' ?>>タイトル</option>
        </select>
        <br>
        <label for="sort_direction">ソート順:</label>
        <select id="sort_direction" name="sort_direction">
            <option value="ASC" <?php echo (HtmlUtil::escape($request->get('sort_direction')) === 'ASC') ? 'selected' : '' ?>>昇順</option>
            <option value="DESC" <?php echo (HtmlUtil::escape($request->get('sort_direction')) === 'DESC') ? 'selected' : '' ?>>降順</option>
        </select>
        <br>
        <input type="submit" value="検索">
    </form>

    <hr>

    <?php foreach ($posts as $postDto): ?>
        <div>
            <?php echo "Post ID: " . HtmlUtil::escape($postDto->post->getId()) ?> <br>
            <?php echo "Title: " . HtmlUtil::escape($postDto->post->getTitle()) ?> <br>
            <?php echo "type: " . HtmlUtil::escape($postDto->title_length_type) ?> <br>
            <?php echo "Content: " . nl2br(HtmlUtil::escape($postDto->post->getContent())) ?> <br>
            <?php echo "登録ユーザ名: " . nl2br(HtmlUtil::escape($postDto->post->getUser()->getName())) ?> <br>
            <a href="/posts/show/<?php echo HtmlUtil::escape($postDto->post->getId()) ?>">詳細</a> |
            <?php if ($postDto->post->getUserId() == $user->getId()): ?>
                <a href="/posts/edit/<?php echo HtmlUtil::escape($postDto->post->getId()) ?>">編集</a> |
                <form method="post" action="/posts/delete/<?php echo HtmlUtil::escape($postDto->post->getId()) ?>" style="display:inline;">
                    <input type="submit" value="削除">
                </form>
            <?php endif; ?>
            
            <hr>
        </div>
    <?php endforeach; ?>
</body>
</html>
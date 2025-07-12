<?php use App\Util\HtmlUtil; ?>

<!DOCTYPE html>
<html>
<head><title>投稿詳細</title></head>
<body>
    <h1>投稿詳細</h1>

    <?php echo "Post ID: " . HtmlUtil::escape($post->getId()) ?> <br>
    <?php echo "Title: " . HtmlUtil::escape($post->getTitle()) ?> <br>
    <?php echo "Content: " . nl2br(HtmlUtil::escape($post->getContent())) ?> <br>
    <?php echo "User Name: " . HtmlUtil::escape($post->getUser()->getName()) ?> <br>

</body>
</html>
<!DOCTYPE html>
<html>
<head><title>投稿詳細</title></head>
<body>
    <h1>投稿詳細</h1>

    <?php echo "Post ID: " . htmlspecialchars($post->getId()) ?> <br>
    <?php echo "Title: " . htmlspecialchars($post->getTitle()) ?> <br>
    <?php echo "Content: " . nl2br(htmlspecialchars($post->getContent())) ?> <br>
    <?php echo "User Name: " . htmlspecialchars($post->getUser()->getName()) ?> <br>

</body>
</html>
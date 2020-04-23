<?php

require '../includes/init.php';

Auth::requiredLogin();

$conn = require '../includes/db.php';

$article = Article::getByID($conn, $_POST['id']);

$published = $article->publish($conn);

?>
<time><?= $published ?></time>
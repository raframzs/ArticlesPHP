<?php

require '../includes/init.php';

Auth::requiredLogin();

$conn = require '../includes/db.php';

if (isset($_GET['id'])) {

    $article = Article::getByID($conn, $_GET['id']);

    if ( ! $article) {
        die("article not found");
    }

} else {
    die("id not supplied, article not found");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $previous_image = $article->image_file;

    if ($article->setImageFile($conn, null)) {

        if ($previous_image) {
            unlink("../uploads/$previous_image");
        }

        Url::redirect("/articles/admin/edit-article-image.php?id={$article->id}");

    }
}

?>
<?php require '../includes/header.php'; ?>
    <li class="nav-item"><a href="new-article.php" class="nav-link text-secondary">New article</a></li>
  </ul>
</div>
<h2>Delete article image</h2>

<form method="post">

    <div class="form-group">
        <p>Are you sure?</p>
        <a href="edit-article-image.php?id=<?= $article->id; ?>">Cancel</a>
    </div>
    
    <button class="btn btn-danger">Delete</button>


</form>

<?php require '../includes/footer.php'; ?>

<?php

    require '../includes/init.php';
    $conn = require '../includes/db.php';

    // Evitar error de que no sea un numero el parametro
    // isset() Determina si una variable está definida y no es NULL
    if (isset($_GET['id'])) {
    
        $article = Article::getByID($conn, $_GET['id']);

        if (!$article) {
            die("article not found");   
        }

    }else {

        die("ID not suplied, article not found");        
    }
    $category_ids = array_column($article->getCategory($conn), 'id');

    $categories = Category::getAll($conn);
    
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
        $article->title = $_POST['title'];
        $article->content = $_POST['content'];
        $article->published_at = $_POST['published_at'];
    
        $category_ids = $_POST['category'] ?? [];
    
        if ($article->update($conn)) {
    
            $article->setCategories($conn, $category_ids);
    
            Url::redirect("/articles/admin/article.php?id={$article->id}");
    
        }
    }
    

?>

<?php require "../includes/header.php"; ?>
</ul>
</div>
<h2 class="display-4 text-primary">Edit Article</h2>

<?php require "includes/article-form.php"; ?>

<?php require "../includes/footer.php"; ?>
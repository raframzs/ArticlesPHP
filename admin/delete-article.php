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

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        
         if($article->delete($conn)){
            
            Url::redirect("/articles/admin/index.php");

        }
        
    }

?>

<?php require "../includes/header.php"; ?>
</ul>
</div>
    <h2 class="display-4 text-primary">Delete Article</h2>
    <form method="post">

        <p>¿Are you sure? Because you will delete
            <strong class="bg-warning"> <?= htmlspecialchars($article->title) ?></strong>
            article.
        </p>

        <button class="btn btn-danger" style=" width:125px; margin-bottom: 10px; ">Delete</button>

        <a href="article.php?id=<?= $article->id; ?>" class="btn btn-light"
           style=" width:125px; margin-bottom: 10px; ">Cancel</a>
    </form>    

<?php require "../includes/footer.php"; ?>
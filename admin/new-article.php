    <?php

        require '../includes/init.php';

        Auth::requiredLogin();

        $article = new Article();

        $category_ids = [];
            
        $conn = require '../includes/db.php';

        $categories = Category::getAll($conn);

        if ($_SERVER["REQUEST_METHOD"] == "POST") {

            $article->title = $_POST["title"];
            $article->content  = $_POST["content"];
            $article->published = $_POST["published"];

            $category_ids = $_POST['category'] ?? [];
            
            if ($article->create($conn)) {

                $article->setCategories($conn, $category_ids);

                Url::redirect("/articles/admin/article.php?id={$article->id}");
            }
        }
    ?>

    <?php require "../includes/header.php"; ?>
    </ul>
    </div>
    <h2 class="display-3 text-warning text-center">New Article</h2><hr>

    <?php require "includes/article-form.php"; ?>

    <?php require "../includes/footer.php"; ?>
  <?php

  require '../includes/init.php';

  Auth::requiredLogin();

  $conn = require '../includes/db.php';

  // Evitar error de que no sea un numero el parametro
  // isset() Determina si una variable está definida y no es NULL
  if (isset($_GET['id'])) {
    $article = Article::getWithCateogies($conn, $_GET['id']);
  } else {
    $article = null;
  }
  ?>
  <?php require '../includes/header.php'; ?>

  <!--Nav of a new item-->
  <li class="nav-item"><a href="new-article.php" class="nav-link text-secondary">New article</a></li>
  </ul>
  </div>

  <!--Sintaxis clave de PHP-->
  <?php if ($article) : ?>

    <!-- Article diplay-->
    <article>

      <!-- title-->
      <h2 class="display-4 text-primary"><?= htmlspecialchars($article[0]['title']); ?></h2>

      <?php if ($article[0]['published']) : ?>
        <time><?= $article[0]['published']; ?></time>
      <?php else : ?>
        Unpublished
      <?php endif; ?>

      <!-- Image + Content -->
      <div class="row">
        <div class="col">

          <!-- Content-->
          <p class="text-justify"><?= htmlspecialchars($article[0]['content']); ?></p>

          <!-- Category if exist-->
          <?php if ($article[0]['category_name']) : ?>
            <small class="text-muted">Categories:
              <?php foreach ($article as $art) : ?>
                <?= htmlspecialchars($art['category_name']); ?>
              <?php endforeach; ?>
            </small>
          <?php endif; ?>

        </div>

        <!-- Image-->
        <div class="col">
          <?php if ($article[0]['image_file']) : ?>
            <img src="/articles/uploads/<?= $article[0]['image_file']; ?>">
          <?php endif; ?>
        </div>

      </div>

    </article>
    <div class="row">
      <!-- Edit link -->
      <a href="edit-article.php?id=<?= $article[0]['id']; ?>" class="btn btn-warning" style=" width:125px; margin-bottom: 10px; margin-left:10px; ">Edit</a>
      <!-- Delete link -->
      <div class="delete"><a href="delete-article.php?id=<?= $article[0]['id']; ?>" class="btn btn-info" style=" width:125px; margin-bottom: 10px; margin-left:10px; ">Delete</a></div>
      <!-- Edit image link -->
      <a href="edit-article-image.php?id=<?= $article[0]['id']; ?>" class="btn btn-info" style=" width:125px; margin-bottom: 10px; margin-left:10px; ">Edit image</a>
    </div>

  <?php else : ?>
    <p>Article not found.</p>
  <?php endif; ?>

  <?php require '../includes/footer.php'; ?>
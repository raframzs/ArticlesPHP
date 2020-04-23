  <?php

  require 'includes/init.php';
  $conn = require 'includes/db.php';

  // Evitar error de que no sea un numero el parametro
  // isset() Determina si una variable está definida y no es NULL
  if (isset($_GET['id'])) {
    $article = Article::getWithCateogies($conn, $_GET['id'], true);
  } else {
    $article = null;
  }
  ?>
  <?php require 'includes/header.php'; ?>
  </ul>
  </div>

  <!--Sintaxis clave de PHP-->
  <?php if ($article) : ?>
    <!-- Article diplay-->
    <article>

      <!-- title-->
      <h2 class="display-4 text-primary"><?= htmlspecialchars($article[0]['title']); ?></h2>

      <!-- date and time -->
      <time datetime="<?= $article[0]['published']; ?>">
        <small class="text-success">
          <?php
          $datetime = new DateTime($article[0]['published']);
          echo $datetime->format("j F, Y");
          ?>
        </small>
      </time>

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
  <?php else : ?>
    <p>Article not found.</p>
  <?php endif; ?>

  <?php require 'includes/footer.php'; ?>
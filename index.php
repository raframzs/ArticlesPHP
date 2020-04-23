  <?php
  
  require 'includes/init.php';

  $conn = require 'includes/db.php';

  // Este operador devuelve su primer operando si está configurado y no NULL. De lo contrario, devolverá su segundo operando.
  $paginator = new Paginator($_GET['page'] ?? 1, 4, Article::getTotal($conn, true));

  $articles = Article::getPage($conn, $paginator->limit, $paginator->offset, true);

  ?>

  <?php require 'includes/header.php'; ?>
  </ul>
  </div>
  <!--coment-->
  <!-- Output if there is not an article -->
  <?php if (empty($articles)) : ?>
    <p>No articles found</p>
  <?php else : ?>

    <!-- Showing the articles -->
    <?php foreach ($articles as $article) : ?>
      <div class="card">
        <!-- Article -->
        <article class="card-body">
          <!-- Title -->
          <div class="card-header">
            <h2><a href="article.php?id=<?= $article['id']; ?>"><?= htmlspecialchars($article['title']); ?></a></h2>
          </div>

          <!-- Category if exist-->
          <?php if ($article['category_names']) : ?>
            <small class="text-muted">Categories:
              <?php foreach ($article['category_names'] as $name) : ?>
                <?= htmlspecialchars($name); ?>
              <?php endforeach; ?>
            </small>
          <?php endif; ?>

          <!-- Content -->
          <p><?= htmlspecialchars($article['content']); ?></p>

          <!-- date and time -->
          <time datetime="<?= $article['published']; ?>">
            <small class="text-muted">
              <?php
              $datetime = new DateTime($article['published']);
              echo $datetime->format("j F, Y");
              ?>
            </small>
          </time>
          
        </article>
      </div>
    <?php endforeach; ?>

    <?php require "includes/Paginator.php"; ?>

  <?php endif; ?>

  <?php require 'includes/footer.php'; ?>
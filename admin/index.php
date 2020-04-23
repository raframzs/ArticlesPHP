  <?php

  require '../includes/init.php';

  Auth::requiredLogin();

  $conn = require '../includes/db.php';

  // Este operador devuelve su primer operando si está configurado y no NULL. De lo contrario, devolverá su segundo operando.
  $paginator = new Paginator($_GET['page'] ?? 1, 6, Article::getTotal($conn));

  $articles = Article::getPage($conn, $paginator->limit, $paginator->offset);


  ?>


  <?php require '../includes/header.php'; ?>

  <li class="nav-item"><a href="new-article.php" class="nav-link text-secondary">New article</a></li>
  </ul>
  </div>
  <h1 class="display-3 text-warning  text-center">Administration</h1>
  <hr>
  <!-- Output if there is not an article -->
  <?php if (empty($articles)) : ?>
    <p>No articles found</p>
  <?php else : ?>

    <table class="table table-bordered">
      <thead class="thead-dark">
        <tr>
          <th class="text-light text-center">Title</th>
          <th class="text-light text-center">Published</th>
        </tr>
      </thead>
      <tbody>
        <!-- Showing the articles -->
        <?php foreach ($articles as $article) : ?>
          <tr>
            <td class="text-center">
              <a class="text-secondary" href="article.php?id=<?= $article['id']; ?>"><?= htmlspecialchars($article['title']); ?></a>
            </td>
            <td class="text-center">
              <?php if ($article['published']) : ?>
                <time class="text-secondary"><?= $article['published']; ?></time>
              <?php else : ?>
                <span class="text-danger">Unpublished</span>
                <button class="publish btn btn-success" data-id="<?= $article['id'] ?>">Publish</button>
              <?php endif; ?>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>

    <?php require "../includes/Paginator.php"; ?>

  <?php endif; ?>

  <?php require '../includes/footer.php'; ?>
    <?php if (!empty($article->errors)) : ?>
      <?php foreach ($article->errors as $e) : ?>
        <li class="list-group-item-light"><?= $e ?></li>
      <?php endforeach; ?>
    <?php endif; ?>

    <form method="post" id="formArticle">

      <!--Title-->
      <div class="form-group">
        <label for="title">Title</label>
        <input name="title" id="title" class="form-control" placeholder="Article Title" value="<?= htmlspecialchars($article->title); ?>">
      </div>

      <!--Content-->
      <div class="form-group">
        <label for="content">Content</label>
        <textarea name="content" id="content" cols="30" rows="4" class="form-control" placeholder="Article content"><?= htmlspecialchars($article->content); ?></textarea>
      </div>

      <!--Publication date and time-->
      <div class="form-group">
        <label for="published">Date and Time</label>
        <input name="published" id="published" class="form-control" value="<?= htmlspecialchars($article->published); ?>">
        <small id="helpId" class="text-muted">Use the box for select the date</small>
      </div>

      <div class="form-group">
        <fieldset>
          <legend>Categories :</legend>
          <?php foreach ($categories as $category) : ?>
            <div>
              <input type="checkbox" name="category[]" value="<?= $category['id'] ?>" id="category<?= $category['id'] ?>" <?php if (in_array($category['id'], $category_ids)) : ?>checked<?php endif; ?>>
              <label for="category<?= $category['id'] ?>"><?= htmlspecialchars($category['name']) ?></label>
            </div>
          <?php endforeach; ?>
        </fieldset>
        <small id="helpId" class="text-muted">You can select many categories</small>
      </div>

      <button class="btn btn-primary">Save</button>

    </form>
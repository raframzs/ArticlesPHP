<?php $base = strtok($_SERVER["REQUEST_URI"], '?'); ?>

    <ul class="nav">
        <?php if($paginator->previous) : ?>
          <li class="nav-item"><a href="<?= $base; ?>?page=<?= $paginator->previous; ?>" class="nav-link">Previous</a></li>
        <?php else: ?>
          <li class="nav-item"><a href="#" class="nav-link disabled">Previous</a></li>
        <?php endif; ?>
        <?php if($paginator->next) : ?>
          <li class="nav-item"><a href="<?= $base; ?>?page=<?= $paginator->next; ?>" class="nav-link">Next</a></li>
        <?php else: ?>
          <li class="nav-item"><a href="#" class="nav-link disabled">Next</a></li>
        <?php endif; ?>         
    </ul>
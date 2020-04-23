    <?php

        require 'includes/init.php';

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $conn = require 'includes/db.php';

            if (User::authenticate($conn, $_POST['username'], $_POST['password'])) {

                Auth::loggin();

                Url::redirect('/articles/index.php');
                
            } else {

                $error = 'loggin incorrect.';
            }
        }

    ?>
    <?php require 'includes/header.php' ?>
    </ul>
    </div>
    <h2 class="display-4 text-primary">Login</h2>

    <!-- Check if loggin is correct -->
    <?php if (!empty($error)) : ?>
        <p class="text-danger"><?= $error; ?></p>
    <?php endif; ?>

    <form method="post">

        <!-- User name input -->
        <div class="form-group">
            <label for="username">Username</label>
            <input name="username" id="username" class="form-control" placeholder="here your username">
        </div>

        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" name="password" id="password" class="form-control" placeholder="your password here">
            <small class="text-muted">Forgot you username?</small>
        </div>

        <button class="btn btn-success" style=" width:125px; margin-bottom: 10px; ">Log In</button>

    </form>

    <?php require 'includes/footer.php' ?>
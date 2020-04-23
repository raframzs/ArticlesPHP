<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
  <meta charset="utf-8">
  <title>My Website</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
  <link rel="stylesheet" href="/articles/css/jquery.datetimepicker.min.css">
  <link rel="stylesheet" href="/articles/css/styles.css">
</head>

<body>
  <div class="container">

    <div class="pb-2 mt-4 mb-2 border-bottom">
      <header>
        <h1 class="display-1">My Blog</h1>
      </header>

      <ul class="nav">
        <li class="nav-item"><a href="/articles/" class="nav-link active">Home</a></li>
        <?php if (Auth::isLoggedIn()) : ?>

          <li class="nav-item"><a href="/articles/admin/" class="nav-link text-secondary">Admin</a></li>
          <li class="nav-item"><a href="/articles/logout.php" class="nav-link text-secondary">Log out</a></li> 

        <?php else : ?>
          <li class="nav-item"><a href="/articles/login.php" class="nav-link">Log in</a></li>
        <?php endif; ?>

        <li class="nav-item text-warning"><a href="/articles/contact.php" class="nav-link">Contact</a></li>

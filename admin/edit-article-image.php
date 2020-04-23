<?php

require '../includes/init.php';
Auth::requiredLogin();
$conn = require '../includes/db.php';

// Evitar error de que no sea un numero el parametro
// isset() Determina si una variable está definida y no es NULL
if (isset($_GET['id'])) {

    $article = Article::getByID($conn, $_GET['id']);

    if (!$article) {
        die("article not found");
    }
} else {

    die("ID not suplied, article not found");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    var_dump($_FILES);
    // $_FILES[] Variables de subida de ficheros HTTP
    try {

        if (empty($_FILES)) {
            throw new Exception('Invalid upload');
        }
        // Types of error occurred when the file is uploading
        switch ($_FILES['file']['error']) {
                // Valor: 0; No hay error, fichero subido con éxito. 
            case UPLOAD_ERR_OK:
                break;
                // Valor: 4; No se subió ningún fichero.     
            case UPLOAD_ERR_NO_FILE:
                throw new Exception('No file uploaded');
                break;
                // Valor: 1; El fichero subido excede la directiva upload_max_filesize de php.ini. 
            case UPLOAD_ERR_INI_SIZE:
                throw new Exception('File is too large (from the server settings)');
                break;

            default:
                throw new Exception('An error occurred');
        }

        // Restrict the file size
        if ($_FILES['file']['size'] > 10000000) {

            throw new Exception('File is too large');
        }

        // Formatos permitidos
        $mime_types = ['image/gif', 'image/png', 'image/jpeg'];

        // Crea un nuevo recurso fileinfo
        $finfo = finfo_open(FILEINFO_MIME_TYPE);

        //Devuelve información sobre un fichero
        $mime_type = finfo_file($finfo, $_FILES['file']['tmp_name']);

        if (!in_array($mime_type, $mime_types)) {

            throw new Exception('Invalid file type');
        }

        // Devuelve información acerca de la ruta de un fichero
        $pathinfo = pathinfo($_FILES["file"]["name"]);

        // Put the base name in the file
        $base = $pathinfo['filename'];

        // Realiza una búsqueda y sustitución de una expresión regular
        $base = preg_replace('/[^a-zA-Z0-9_-]/', "_", $base);

        //Obtiene parte de una cadena de caracteres
        $base = mb_substr($base, 0, 200);


        $filename = $base . "." . $pathinfo['extension'];

        $destination = "../uploads/$filename";

        $i = 1;
        // Comprueba si existe un fichero o directorio
        while (file_exists($destination)) {
            $filename = $base . "-$i" . "." . $pathinfo['extension'];
            $destination = "../uploads/$filename";
            $i++;
        }
        // Mueve un archivo subido a una nueva ubicación
        if (move_uploaded_file($_FILES['file']['tmp_name'], $destination)) {

            if ($article->setImageFile($conn, $filename)) {

                if ($previous_image) {
                    unlink("../uploads/$previous_image");
                }

                Url::redirect("/articles/admin/article.php?id={$article->id}");
            }
        } else {
            throw new Exception("Unable to move uploaded file");
        }
    } catch (Exception $e) {
        echo $e->getMessage();
    }
}


?>

<?php require "../includes/header.php"; ?>

<li class="nav-item"><a href="new-article.php" class="nav-link text-secondary">New article</a></li>
</ul>
</div>
<h2 class="display-4 text-primary text-center">Edit Article Image</h2><hr>

<div class="row">
    <div class="col">

        <!-- Morstrar la imagen si la tiene-->
        <?php if ($article->image_file) : ?>
            <img src="/uploads/<?= $article->image_file; ?>"><br>

            <!-- Eliminar imagen actual-->
            <div class="delete"><a class="btn btn-danger" href="delete-article-image.php?id=<?= $article->id; ?>" style="margin-top: 10px;">Delete</a></div>
            
        <?php endif; ?>
        
        <!-- Si hay algun error..-->
        <?php if (isset($error)) : ?>
            <p><?= $error ?></p>
        <?php endif; ?>
    </div>
    <div class="col">
        <!-- Si se desea cargar una nueva imagen!-->
        <form method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label class="display-4" for="file">Change image File</label>
                <input type="file" name="file" id="file" class="form-control">
            </div>
            <button class="btn btn-success">Upload</button>

        </form>
    </div>
</div>

<?php require "../includes/footer.php"; ?>
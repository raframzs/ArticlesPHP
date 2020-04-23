    <?php
        /**
         * Initializations
         * 
         * Register an autoloader, start or resume the session etc.
         */
        spl_autoload_register(function ($class)
        {
            require dirname(__DIR__)."/classes/{$class}.php";
        });
        
        // Crea una sesión o reanuda la actual basada en un identificador de sesión pasado mediante una petición GET o POST, o pasado mediante una cookie.
        session_start();

        require dirname(__DIR__).'/config.php';

        function errorHandler($level, $message, $file, $line)
        {
            throw new ErrorException($message, 0, $level, $file, $line);
            
        }

        function exceptionHandler($exception)
        {
            http_response_code(500);
            if (SHOW_ERROR_DETAIL) {
                echo "<h1 class='display-1 text-danger'>An error occured</h1>";
                echo "<p class='bg-warning'>Uncaught exception: '".get_class($exception)."'</p>";
                echo "<p>".$exception->getMessage()."</p>";
                echo "<p>Stack trace: <pre>" . $exception->getTraceAsString() . "</pre></p>";
                echo "<p>In file '" . $exception->getFile() ."' on line " . $exception->getLine() . "</p>";
            }else{
                echo "<h1>An error occured.</h1>";
            }

            exit();

        }

        set_error_handler('errorHandler');
        set_exception_handler('exceptionHandler');
    ?>
     
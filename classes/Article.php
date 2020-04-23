<?php

/**
 * Article
 * 
 * A piece of writing for publication
 */
class Article
{
    /**
     * Unique identifier
     *  @var intiger
     */
    public $id;
    /**
     * The article title
     * @var string
     */
    public $title;
    /**
     * The article content
     * @var string
     */
    public $content;
    /**
     * The article published date
     * @var datetime 'yyy-mm-dd hh:mm:ss'
     */
    public $published;
    /**
     * Validation errors
     * @var array
     */
    public $errors = [];
    /**
     * Path to the image
     * @var string
     */
    public $image_file;

    /**
     * Get all the articles
     * 
     * @param PDO $conn Connection to database
     * 
     * @return array An asscociative array of all the articles record
     */
    public static function getAll(PDO $conn)
    {
        $sql = "SELECT * FROM article ORDER BY published;";

        //Ejecuta una sentencia SQL, devolviendo un conjunto de resultados como un objeto PDOStatement   
        $results = $conn->query($sql);

        // Devuelve un array que contiene todas las filas del conjunto de resultados   
        return $results->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get a page of articles
     *
     * @param object $conn Connection to the database
     * @param integer $limit Number of records to return
     * @param integer $offset Number of records to skip
     *
     * @return array An associative array of the page of article records
     */
    public static function getPage($conn, $limit, $offset, bool $only_published=false)
    {   
        $condition = $only_published ? ' WHERE published IS NOT NULL': '';

        $sql = "SELECT a.*, category.name AS category_name
                FROM (
                    SELECT *
                    FROM article
                    $condition
                    ORDER BY published
                    LIMIT :limit
                    OFFSET :offset) AS a
                LEFT JOIN article_category
                ON a.id = article_category.article_id
                LEFT JOIN category
                ON article_category.category_id = category.id";

        $stmt = $conn->prepare($sql);

        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);

        $stmt->execute();

        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Consolidate the article records into a single element for each article,
        // putting the category names into an array
        $articles = [];

        $previous_id = null;

        foreach ($results as $row) {

            $article_id = $row['id'];

            if ($article_id != $previous_id) {
                $row['category_names'] = [];

                $articles[$article_id] = $row;
            }

            $articles[$article_id]['category_names'][] = $row['category_name'];

            $previous_id = $article_id;
        }

        return $articles;
    }

    /**
     * Get the article record based on the ID
     * 
     * @param object $conn Connection to the database
     * @param integer $id the article ID
     * @param string $columns Optional list of columns for the select, defaults to*
     * 
     * @return mixed An object of this class, or null if not found.
     */
    public static function getByID(PDO $conn, $id, $columns = '*')
    {

        $sql = "SELECT $columns
                    FROM article
                    WHERE id=:id";

        //  Prepara una sentencia para su ejecución y devuelve un objeto sentencia 
        $stmt = $conn->prepare($sql); // prepare

        //Vincula un valor al parámetro de sustitución con nombre o de signo de interrogación de la sentencia SQL que se utilizó para preparar la sentencia. 
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);

        // Establece el modo de obtención para esta sentencia 
        $stmt->setFetchMode(PDO::FETCH_CLASS, 'Article');

        // Ejecuta una sentencia preparada
        if ($stmt->execute()) { // execute

            return $stmt->fetch(); // fetch
        }
    }
    /**
     * Get the article record based on the id along with the categies, if any
     * 
     * @param object $conn Connection to the database
     * @param integer $id article ID
     * 
     * @return array The article data with categories
     */
    public static function getWithCateogies(PDO $conn, int $id, bool $only_published= false)
    {
        $sql =
            "SELECT article.*, category.name AS category_name
                    FROM article
                    LEFT JOIN article_category
                    ON article.id = article_category.article_id
                    LEFT JOIN category
                    ON article_category.category_id = category.id
                    WHERE article.id = :id";

        if ($only_published) {
            $sql .= ' AND article.published IS NOT NULL';
        }

        $stmt =  $conn->prepare($sql);
        $stmt->bindValue(":id", $id, PDO::PARAM_INT);

        try {
            $stmt->execute();
        } catch (Exception $e) {
            echo $e->getMessage();
        }

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    /**
     * Get the article's categories
     * 
     * @param PDO $conn Connection with the database
     * 
     * @return array The caregory data
     */
    public function getCategory(PDO $conn)
    {
        $sql =
            "SELECT category.*
            FROM category
            JOIN article_category
            ON category.id = article_category.category_id
            WHERE article_id = :id";

        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':id', $this->id, PDO::PARAM_INT);

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Set the article categories
     *
     * @param object $conn Connection to the database
     * @param array $ids Category IDs
     *
     * @return void
     */
    public function setCategories($conn, $ids)
    {
        if ($ids) {

            $sql = "INSERT IGNORE INTO article_category (article_id, category_id)
                        VALUES ";

            $values = [];

            foreach ($ids as $id) {
                $values[] = "({$this->id}, ?)";
            }

            $sql .= implode(", ", $values);

            $stmt = $conn->prepare($sql);

            foreach ($ids as $i => $id) {
                $stmt->bindValue($i + 1, $id, PDO::PARAM_INT);
            }

            $stmt->execute();
        }

        $sql = "DELETE FROM article_category
                WHERE article_id = {$this->id}";

        if ($ids) {
            $placeholder = array_fill(0, count($ids), '?');
            $sql .= " AND category_id NOT IN (".implode(",",$placeholder).")";
        }
        
        $stmt = $conn->prepare($sql);
        foreach ($ids as $i => $id) {
            $stmt->bindValue($i + 1, $id, PDO::PARAM_INT);
        }
        $stmt->execute();


    }

    /**
     * Update the article with it's current property values
     * 
     * @param PDO $conn Connection to the database
     * 
     * @return boolean True if the update was successful
     */
    public function update(PDO $conn)
    {
        if ($this->validate()) {
            // usamos signos '?' para proteger el app de un SQL inyection
            $sql = "UPDATE article 
                    SET title = :title,
                    content = :content,
                    published = :published
                    WHERE id=:id";

            $stmt = $conn->prepare($sql);

            $stmt->bindValue(':id', $this->id, PDO::PARAM_INT);
            $stmt->bindValue(':title', $this->title, PDO::PARAM_STR);
            $stmt->bindValue(':content', $this->content, PDO::PARAM_STR);

            if ($this->published == '') {
                $stmt->bindValue(':published', null, PDO::PARAM_NULL);
            } else {
                $stmt->bindValue(':published', $this->published, PDO::PARAM_STR);
            }

            return $stmt->execute();
        } else {
            return false;
        }
    }

    /**
     * Validate the articles properties
     * 
     * @param string $title Title, required
     * @param string $content Content, required
     * @param string $published DataTime, yyyy-mm-dd hh:mm:ss if not blank
     * 
     * @return boolean True if the current properties are valid, false otherwise
     */

    protected function validate()
    {

        if ($this->title == "") {
            $this->errors[] = "You must provide a title.";
        }
        if ($this->content == "") {
            $this->errors[] = "We can't see any content from here.";
        }

        if ($this->published != "") {
            $date_time = date_create_from_format("Y-m-d H:i:s", $this->published);

            if ($date_time === false) {

                $this->errors[] = "Invalid data and time";
            } else {

                $date_errors = date_get_last_errors();

                if ($date_errors['warning_count'] > 0) {
                    $this->errors[] = 'Invalid date and time.';
                }
            }
        }
        return empty($this->errors);
    }

    /**
     * Delete the current article
     * 
     * @param object $conn Connection to the database
     * 
     * @return boolean True if the delete was successful, false otherwise
     */
    public function delete(PDO $conn)
    {
        $sql = "DELETE FROM article
                        WHERE id=:id";

        $stmt = $conn->prepare($sql);

        $stmt->bindValue(':id', $this->id, PDO::PARAM_INT);


        return $stmt->execute();
    }

    /**
     * Insert a new article with it's current property values
     * 
     * @param PDO $conn Connection to the database
     * 
     * @return boolean True if the insert was successful, false otherwise
     */
    public function create(PDO $conn)
    {
        if ($this->validate()) {
            // usamos signos '?' para proteger el app de un SQL inyection
            $sql = "INSERT INTO article (title, content, published)
                            VALUES (:title, :content, :published)";

            $stmt = $conn->prepare($sql);

            $stmt->bindValue(':title', $this->title, PDO::PARAM_STR);
            $stmt->bindValue(':content', $this->content, PDO::PARAM_STR);

            if ($this->published == '') {
                $stmt->bindValue(':published', null, PDO::PARAM_NULL);
            } else {
                $stmt->bindValue(':published', $this->published, PDO::PARAM_STR);
            }

            if ($stmt->execute()) {
                // Devuelve el ID de la última fila o secuencia insertada 
                $this->id = $conn->lastInsertId();
                return true;
            }
        } else {
            return false;
        }
    }

    /**
     * Get a count of the total number of records
     * 
     * @param object $conn Connection to the database
     * 
     * @return integer The total number of records
     */
    public static function getTotal(PDO $conn, bool $only_published=false)
    {   
        $condition = $only_published ? ' WHERE published IS NOT NULL': '';
        return $conn->query("SELECT COUNT(*) FROM article$condition")->fetchColumn();
    }
    /**
     * Update the image file property
     * @param object $conn Connection to the data base
     * @param string $filename File name of the image file
     * 
     * @return boolean true if it was successfull, false otherwise
     */
    public function setImageFile(PDO $conn, $filename)
    {
        $sql = "UPDATE article
                        SET image_file = :image_file
                        WHERE id = :id";

        $stmt = $conn->prepare($sql);

        $stmt->bindValue(':id', $this->id, PDO::PARAM_INT);
        $stmt->bindValue(':image_file', $filename, PDO::PARAM_STR);

        return $stmt->execute();
    }

    /**
     * Publish the article, setting the published_at field to the current date and time
     *
     * @param object $conn Connection to the database
     *
     * @return mixed The published at date and time if successful, null otherwise
     */
    public function publish($conn)
    {
        $sql = "UPDATE article
                SET published = :published
                WHERE id = :id";

        $stmt = $conn->prepare($sql);

        $stmt->bindValue(':id', $this->id, PDO::PARAM_INT);

        $published = date("Y-m-d H:i:s");
        $stmt->bindValue(':published', $published, PDO::PARAM_STR);

        if ($stmt->execute()) {
            return $published;
        }
    }
}

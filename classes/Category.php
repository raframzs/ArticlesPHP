<?php 

    /**
     * Category
     * 
     * Groupings for articles
     */
    class Category  
    {
        /**
        * Get all the categories
        * 
        * @param PDO $conn Connection to database
        * 
        * @return array An asscociative array of all the articles record
        */
        public static function getAll(PDO $conn)
        {
            $sql = "SELECT * FROM category ORDER BY name;"; 
            
            //Ejecuta una sentencia SQL, devolviendo un conjunto de resultados como un objeto PDOStatement   
            $results = $conn->query($sql);

            // Devuelve un array que contiene todas las filas del conjunto de resultados   
            return $results->fetchAll(PDO::FETCH_ASSOC);
        }
    }
    
?>
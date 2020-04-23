<?php
    /**
     * Paginator
     * 
     * Data for selecting a page of records
     */
    class Paginator
    {   
        /**
         * Number of records to return
         * @var integer
         */
        public $limit;
        /**
         * Number of records to skip before the page
         * @var integer
         */
        public $offset;
        /**
         * Previous page number
         * @var integer
         */
        public $previous;
        /**
         * Next page number
         * @var integer
         */
        public $next;

        /**
         * Constructor
         * 
         * @param integer $page Page number
         * @param integer $records_per_page Numbers of records per page
         */
        public function __construct($page, $records_per_page, $total_recods)   
        {
                $this->limit = $records_per_page;

                /**
                 * filter_var() Filtra una variable con el filtro que se indique
                 * 
                 * FILTER_VALIDATE_INT Valida un valor como integer, opcionalmente 
                 * desde el rango especificado, y lo convierte a int en case de éxito.
                 */
                $page = filter_var($page, FILTER_VALIDATE_INT, 
                [
                    'options' => ['default'=> 1,
                                  'min_range' => 1]
                ]); 

                if ($page > 1) {
                    $this->previous = $page - 1;
                }

                $total_pages = ceil($total_recods / $records_per_page);

                if ($page < $total_pages) {
                    $this->next = $page + 1;
                }

                $this->next = $page + 1;
                $this->offset = $records_per_page * ($page - 1);
        }
    }
?>
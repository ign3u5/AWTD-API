<?php
    require_once __DIR__."/../handlers/responseHandler.php";
    require_once __DIR__."/../config.php";
    class Database {
        private $server;
        private $name;
        private $user;
        private $pass;

        public $connection;

        public function __construct()
        {
            $this->server = DatabaseConnectionDetails::SERVER;
            $this->name = DatabaseConnectionDetails::NAME;
            $this->user = DatabaseConnectionDetails::USER;
            $this->pass = DatabaseConnectionDetails::PASS;
        }

        public function getConnection()
        {
            $this->connection = null;
            try{
                $this->connection = new PDO("mysql:host=$this->server;dbname=$this->name", $this->user, $this->pass, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_TIMEOUT => 5, PDO::MYSQL_ATTR_FOUND_ROWS => true));
            }
            catch (PDOException $ex)
            {
                echo "PDO Connection to database failed: " . $ex->getMessage();
            }
            return $this->connection;
        }
    }
    function Sanitise(&$data)
    {
        $data = htmlspecialchars(strip_tags($data));
    }
    function RespondWithExecutionError()
    {
        return NewResponse(500, "Error retrieving data from database");
    }
?>
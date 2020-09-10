<?php
class Database{
  
    // own database credentials
    private $host = "127.0.0.1";
    private $db_name = "casdnbox";
    private $username = "root";
    private $password = "things";
    public $conn;
  
    // get database connection
    public function getConnection(){
  
        $this->conn = null;
  
        try{
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
        }catch(PDOException $exception){
            echo "Connection error: " . $exception->getMessage();
        }
  
        return $this->conn;
    }
}
?>
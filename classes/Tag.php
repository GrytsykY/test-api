<?php

class Tag
{
    private $conn;
    private $table_name = "tags";

    public $id;
    public $name;
    public $created;

    public function __construct($db){
        $this->conn = $db;
    }

    function create(){

        $query = "INSERT INTO
                " . $this->table_name . "
            SET
                name=:name, created=:created";

        $stmt = $this->conn->prepare($query);

        $this->name=htmlspecialchars(strip_tags($this->name));
        $this->created=htmlspecialchars(strip_tags($this->created));

        $stmt->bindParam(":name", $this->name);
        $stmt->bindParam(":created", $this->created);

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    public function readAll(){
        $query = "SELECT
                    *
                FROM
                    " . $this->table_name . "
                ORDER BY
                    name";

        $stmt = $this->conn->prepare( $query );
        $stmt->execute();

        return $stmt;
    }

    public function read(){

        $query = "SELECT
                id, name
            FROM
                " . $this->table_name . "
            ORDER BY
                name";

        $stmt = $this->conn->prepare( $query );
        $stmt->execute();

        return $stmt;
    }

    function readOne() {

        $query = "SELECT * FROM `tags` JOIN `tasks` ON tags.id=tasks.tag_id WHERE tags.id=?
            ";

        $stmt = $this->conn->prepare( $query );

        $stmt->bindParam(1, $this->id);

        $stmt->execute();

        return $stmt;
    }
}
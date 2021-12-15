<?php

class Task
{
    private $conn;
    private $table_name = "tasks";

    public $id;
    public $name;
    public $description;
    public $tag_id;
    public $tag_name;
    public $created;

    public function __construct($db){
        $this->conn = $db;
    }

    function read()
    {

        $query = "SELECT
                tags.name as tag_name, t.id, t.name, t.description, t.tag_id, t.created
            FROM
                " . $this->table_name . " t
                LEFT JOIN
                    tags 
                        ON t.tag_id = tags.id
            ORDER BY
                t.created DESC";

        $stmt = $this->conn->prepare($query);

        $stmt->execute();

        return $stmt;
    }

    function create(){

        $query = "INSERT INTO
                " . $this->table_name . "
            SET
                name=:name, description=:description, tag_id=:tag_id, created=:created";

        $stmt = $this->conn->prepare($query);

        $this->name=htmlspecialchars(strip_tags($this->name));
        $this->description=htmlspecialchars(strip_tags($this->description));
        $this->tag_id=htmlspecialchars(strip_tags($this->tag_id));
        $this->created=htmlspecialchars(strip_tags($this->created));

        $stmt->bindParam(":name", $this->name);
        $stmt->bindParam(":description", $this->description);
        $stmt->bindParam(":tag_id", $this->tag_id);
        $stmt->bindParam(":created", $this->created);

        if ($stmt->execute()) {
            return true;
        }

        return false;
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
<?php

class Bucketlist
{
    private $conn;

    // Konstruktor som tar emot databasanslutningen
    public function __construct($db)
    {
        $this->conn = $db;
    }

    // Hämta alla saker från bucketlisten
    public function getAll()
    {
        $sql = "SELECT * FROM bucketlist_items ORDER BY priority ASC";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Lägg till en ny sak
    public function add($name, $description, $priority)
    {
        $sql = "INSERT INTO bucketlist_items (name, description, priority)
                VALUES (:name, :description, :priority)";

        $stmt = $this->conn->prepare($sql);

        return $stmt->execute([
            ':name' => $name,
            ':description' => $description,
            ':priority' => $priority
        ]);
    }

    // Radera en sak
    public function delete($id)
    {
        $sql = "DELETE FROM bucketlist_items WHERE id = :id";

        $stmt = $this->conn->prepare($sql);

        return $stmt->execute([
            ':id' => $id
        ]);
    }

    public function update($id, $name, $description, $priority)
{
    $sql = "UPDATE bucketlist_items
            SET name = :name,
                description = :description,
                priority = :priority
            WHERE id = :id";

    $stmt = $this->conn->prepare($sql);

    return $stmt->execute([
        ':id' => $id,
        ':name' => $name,
        ':description' => $description,
        ':priority' => $priority
    ]);
}
}
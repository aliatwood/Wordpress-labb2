
<?php

require_once __DIR__ . '/../classes/Database.php';
require_once __DIR__ . '/../classes/Bucketlist.php';

$database = new Database();
$conn = $database->connect();

$bucketlist = new Bucketlist($conn);


<?php

require_once __DIR__ . '/../config/database.php';

$database = new Database();

$db = $database->connect();

if ($db) {
    echo "<h1 style='color:green'>KẾT NỐI MYSQL THÀNH CÔNG!</h1>";
    echo "<p>Database: phone_store</p>";
}

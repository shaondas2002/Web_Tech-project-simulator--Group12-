<?php
function getConnection(): mysqli {
    $db_host = "localhost";
    $db_user = "root";
    $db_pass = "";
    $db_name = "ecommerce_store";

    $conn = new mysqli($db_host, $db_user, $db_pass, $db_name);
    if ($conn->connect_error) {
        die("DB Connection Failed: " . $conn->connect_error);
    }
    $conn->set_charset('utf8mb4');
    return $conn;
}
?>

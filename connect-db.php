<?php
// Koneksi ke database
$host = "localhost";
$user = "remote_user";
$password = "password123";
$dbname = "db_cms-si";

$conn = new mysqli($host, $user, $password, $dbname);
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}
?>
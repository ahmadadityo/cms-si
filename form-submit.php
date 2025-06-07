<?php
include 'connect-db.php';

// Ambil data dari form
$id = $_POST['id'];
$name = $_POST['name'];
$name_table = $_POST['name_table'];

// Hindari SQL Injection
$id = $conn->real_escape_string($id);
$name = $conn->real_escape_string($name);
$name_table = $conn->real_escape_string($name_table);

// Simpan ke database
if (empty($id)) {
    $sql = "INSERT INTO tb_form (id, name, name_table) VALUES (uuid(), '$name', '$name_table')";
} else {
    $sql = "UPDATE tb_form SET name = '$name', name_table = '$name_table' WHERE id = '$id'";
}

if ($conn->query($sql) === TRUE) {
    echo "Data berhasil disimpan!";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

// Tutup koneksi
$conn->close();

header('Location: form.php');
?>

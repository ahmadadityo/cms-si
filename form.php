<?php
include 'connect-db.php';

// Ambil data dari tb_form
$sql = "SELECT id, name, name_table FROM tb_form";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Daftar Form</title>
</head>
<body>

<h2>Daftar Form</h2>

<a href="form-input.php">Tambah</a><br/><br/>

<table border="1" cellpadding="10" cellspacing="0">
    <thead>
        <tr>
            <th>Nama</th>
            <th>Tabel</th>
            <th>Kelola</th>
        </tr>
    </thead>
    <tbody>
        <?php if ($result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['name']) ?></td>
                    <td><?= htmlspecialchars($row['name_table']) ?></td>
                    <td>
                        <a href="/cms-si/form-input.php?id=<?= $row['id'] ?>">Pilih</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr>
                <td colspan="2">Data tidak ditemukan.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>

</body>
</html>

<?php $conn->close(); ?>

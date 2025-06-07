<?php
include 'connect-db.php';

$name = '';
$name_table = '';
$id_form = isset($_GET['id']) ? $_GET['id'] : '';

// Ambil nama form berdasarkan ID
if (!empty($id_form)) {
    $sql = "SELECT name, name_table FROM tb_form WHERE id = '$id_form'"; //echo $sql;die('trace');
    $result = $conn->query($sql);
    foreach ($result as $field) {
        $name = $field['name'];
        $name_table = $field['name_table'];
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Form Input</title>
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #999;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #eee;
        }
    </style>
</head>
<body>
    <h2>Form Input</h2>
    <form action="form-submit.php" method="post">
        <input type="hidden" id="id" name="id" value="<?= htmlspecialchars($id_form); ?>">
        
        <input type="submit" value="Simpan">
        <a href="form-submit-delete.php?id=<?= urlencode($id_form); ?>">Hapus</a>

        <br><br>

        <label for="name">Nama:</label>
        <input type="text" id="name" name="name" value="<?= htmlspecialchars($name); ?>" required>
        <br><br>

        <label for="name_table">Tabel:</label>
        <input type="text" id="name_table" name="name_table" value="<?= htmlspecialchars($name_table); ?>" required>
    </form> 

    <br>
    <a href="form-input-entry.php?id_form=<?= urlencode($id_form); ?>">Tambah Entri</a>
    <br><br>

    <!-- Tabel Data -->
    <table>
        <thead>
            <tr>
                <th>Label</th>
                <th>Type</th>
                <th>Mandatory</th>
                <th>Ordering</th>
                <th>Kelola</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Ambil data fields berdasarkan id_form
            if (!empty($id_form)) {
                $sql_fields = "SELECT id, label, type, mandatory, ordering FROM tb_form_fields WHERE id_form = '$id_form' ORDER BY ordering ASC";
                $result_fields = $conn->query($sql_fields);

                if ($result_fields->num_rows > 0) {
                    while ($row = $result_fields->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row['label']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['type']) . "</td>";
                        echo "<td>" . ($row['mandatory'] ? 'Ya' : 'Tidak') . "</td>";
                        echo "<td>" . htmlspecialchars($row['ordering']) . "</td>";
                        echo "<td><a href='form-input-entry.php?id_form=" . urlencode($id_form) . "&id=" . urlencode($row['id']) . "'>Pilih</a></td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='5'>Belum ada field ditambahkan.</td></tr>";
                }
            }
            ?>
        </tbody>
    </table>
</body>
</html>

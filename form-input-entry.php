<?php
include 'connect-db.php';

// Proses saat form disubmit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_form = $_POST['id_form'];
    $id = $_POST['id'];
    $label = $_POST['label'];
    $field_id = $_POST['field_id'];
    $type = $_POST['type'];
    $length = is_numeric($_POST['length']) ? intval($_POST['length']) : 0;
    $mandatory = isset($_POST['mandatory']) ? 1 : 0;
    $ordering = is_numeric($_POST['ordering']) ? intval($_POST['ordering']) : 0;
    $default_value = ($type!='checkbox')? $_POST['default_value'] : 1;
    $searchable = isset($_POST['searchable']) ? 1 : 0;
    $options = $_POST['options'] ?? '';

    if (empty($id)) { 
        $sql = "INSERT INTO tb_form_fields (id, id_form, label, field_id, type, length, mandatory, ordering, default_value, searchable) 
            VALUES (uuid(), '$id_form', '$label', '$field_id', '$type', $length, $mandatory, $ordering, '$default_value', $searchable)";
    } else { 
        $sql = "UPDATE tb_form_fields 
            SET label = '$label', field_id = '$field_id', type = '$type', length = $length, 
                mandatory = $mandatory, ordering = $ordering, default_value = '$default_value',
                searchable = $searchable, options = '$options'
            WHERE id_form = '$id_form' AND id = '$id'"; 
    }

    if ($conn->query($sql) === TRUE) {
        header('Location: form-input.php?id=' . $id_form);
        exit;
    } else {
        echo "Gagal menyimpan data: " . $conn->error;
    }
}

// Nilai default untuk form
$label = '';
$field_id = '';
$type = '';
$length = '';
$mandatory = '';
$ordering = '';
$default_value = '';
$searchable = 0;
$options = '';

if (isset($_GET['id'])) {
    $sql = "SELECT label, field_id, type, length, mandatory, ordering, default_value, searchable, options
            FROM tb_form_fields 
            WHERE id_form = '$_GET[id_form]' AND id = '$_GET[id]'";
    $result = $conn->query($sql);
    foreach ($result as $field) {
        $label = $field['label'];
        $field_id = $field['field_id'];
        $type = $field['type'];
        $length = $field['length'];
        $mandatory = $field['mandatory'];
        $ordering = $field['ordering'];
        $default_value = $field['default_value'];
        $searchable = $field['searchable'];
        $options = $field['options'];
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Form Input Field</title>
    <script>
        function toggleSelectFields() {
            const type = document.querySelector('select[name="type"]').value;
            const extraFields  = document.getElementById('select-extra-fields');
            const searchableField = document.getElementById('searchable-container');
            const lengthField = document.getElementById('length-container');

            if (type === 'text' || type === 'textarea') {
                lengthField.style.display = 'block';
            } else {
                lengthField.style.display = 'none';
            }

            // Tampilkan extra fields jika type 'select' atau 'radio'
            if (type === 'select' || type === 'radio') {
                extraFields.style.display = 'block';
            } else {
                extraFields.style.display = 'none';
            }

            // Tampilkan searchable hanya jika type == 'select'
            if (type === 'select') {
                searchableField.style.display = 'block';
            } else {
                searchableField.style.display = 'none';
            }

        }

        window.onload = toggleSelectFields;
    </script>
</head>
<body>
    <h2>Entri Data ke tb_form_fields</h2>
    <form method="post" action="">
        <input type="hidden" name="id_form" value="<?= $_GET['id_form']; ?>">
        <input type="hidden" name="id" value="<?= $_GET['id']; ?>">

        <label>Label:</label><br>
        <input type="text" name="label" value="<?= $label; ?>" required><br><br>

        <label>Field:</label><br>
        <input type="text" name="field_id" value="<?= $field_id; ?>" required><br><br>

        <label>Type:</label><br>
        <select name="type" onchange="toggleSelectFields()" required>
            <option value="">-- Pilih Type --</option>
            <option value="text" <?= ($type == 'text') ? 'selected' : '' ?>>text</option>
            <option value="textarea" <?= ($type == 'textarea') ? 'selected' : '' ?>>textarea</option>
            <option value="select" <?= ($type == 'select') ? 'selected' : '' ?>>select</option>
            <option value="radio" <?= ($type == 'radio') ? 'selected' : '' ?>>radio</option>
            <option value="checkbox" <?= ($type == 'checkbox') ? 'selected' : '' ?>>checkbox</option>
            <option value="datetime" <?= ($type == 'datetime') ? 'selected' : '' ?>>datetime</option>
            <option value="date" <?= ($type == 'date') ? 'selected' : '' ?>>date</option>
            <option value="time" <?= ($type == 'time') ? 'selected' : '' ?>>time</option>
        </select><br><br>

        <!-- Tambahan jika type == select / radio -->
        <div id="select-extra-fields" style="display:none;">
            <label>Options:</label><br>
            <textarea name="options" rows="4" cols="50"><?= $options; ?></textarea><br><br>

            <label>Default Value:</label><br>
            <input type="text" name="default_value" value="<?= htmlspecialchars($default_value); ?>"><br><br>

            <div id="searchable-container" style="display:none;">
                <label>Searchable:</label>
                <input type="checkbox" name="searchable" value="1" <?= ($searchable == 1) ? 'checked' : ''; ?>><br><br>
            </div>
        </div>

        <div id="length-container" style="display:none;">
            <label>Length:</label><br>
            <input type="number" name="length" min="0" value="<?= $length; ?>" required><br><br>
        </div>

        <label>Mandatory:</label>
        <input type="checkbox" name="mandatory" value="1" <?= ($mandatory == 1) ? 'checked' : ''; ?>><br><br>

        <label>Ordering:</label><br>
        <input type="number" name="ordering" min="0" value="<?= $ordering; ?>" required><br><br>

        <input type="submit" value="Simpan">
    </form>
</body>
</html>

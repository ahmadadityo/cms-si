<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>CMS-SI</title>

    <!-- CSS Select2 -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <!-- CSS Flatpickr -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- JS Select2 -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <!-- JS Flatpickr -->
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

    <script>
    $(document).ready(function() {
        // Select2 untuk select box
        $('.select2').select2();

        // Flatpickr konfigurasi:
        $(".datetime").flatpickr({
            enableTime: true,
            dateFormat: "d-m-Y H:i",
            time_24hr: true
        });

        $(".dateonly").flatpickr({
            enableTime: false,
            dateFormat: "d-m-Y"
        });

        $(".timeonly").flatpickr({
            noCalendar: true,
            enableTime: true,
            dateFormat: "H:i",
            time_24hr: true
        });
    });
    </script>
</head>
<body>

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

// Query data form fields
$sql = "SELECT field_id, label, type, length, mandatory, default_value, options, searchable 
        FROM tb_form_fields ORDER BY ordering ASC";
$result = $conn->query($sql);

$formFields = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $field = [
            'field_id' => $row['field_id'],
            'label' => $row['label'],
            'type' => $row['type'],
            'length' => $row['length'] ?? '',
            'mandatory' => (bool)$row['mandatory'],
        ];

        if (!empty($row['default_value'])) {
            $field['default'] = $row['default_value'];
        }

        if (!empty($row['options'])) {
            $field['options'] = explode(',', $row['options']);
        }

        if (!empty($row['searchable'])) {
            $field['searchable'] = (bool)$row['searchable'];
        }

        $formFields[] = $field;
    }
} else {
    echo "Tidak ada field yang ditemukan.";
}
$conn->close();

echo '<form method="post" action="">';

foreach ($formFields as $field) {
    $id = $field['field_id'];
    $label = $field['label'];
    $type = $field['type'];
    $length = $field['length'] ?? '';
    $mandatory = !empty($field['mandatory']) ? 'required' : '';
    $default = $field['default'] ?? '';
    $searchable = $field['searchable'] ?? false;
    $options = $field['options'] ?? [];

    echo '<div style="margin-bottom:15px;">';

    switch ($type) {
        case 'text':
            echo '<label for="' . $id . '">' . ucfirst($label) . ':</label>';
            echo '<input type="text" name="' . $id . '" id="' . $id . '" maxlength="' . $length . '" ' . $mandatory . '>';
            break;

        case 'textarea':
            echo '<label for="' . $id . '">' . ucfirst($label) . ':</label>';
            echo '<textarea name="' . $id . '" id="' . $id . '" maxlength="' . $length . '" ' . $mandatory . '></textarea>';
            break;

        case 'select':
            echo '<label for="' . $id . '">' . ucfirst($label) . ':</label>';
            $selectClass = $searchable ? 'select2' : '';
            echo '<select name="' . $id . '" id="' . $id . '" class="' . $selectClass . '" ' . $mandatory . '>';
            echo '<option value="">-- Pilih --</option>';
            foreach ($options as $option) {
                $selected = ($option === $default) ? 'selected' : '';
                echo '<option value="' . $option . '" ' . $selected . '>' . $option . '</option>';
            }
            echo '</select>';
            break;

        case 'radio':
            echo '<label>' . ucfirst($label) . ':</label><br>';
            foreach ($options as $option) {
                $checked = ($option === $default) ? 'checked' : '';
                echo '<input type="radio" name="' . $id . '" value="' . $option . '" ' . $checked . ' ' . $mandatory . '> ' . $option . '<br>';
            }
            break;

        case 'checkbox':
            $checked = ($default == '1') ? 'checked' : '';
            echo '<input type="checkbox" name="' . $id . '" id="' . $id . '" value="1" ' . $checked . ' ' . $mandatory . '>';
            echo '<label for="' . $id . '"> ' . $label . '</label>';
            break;

        case 'datetime':
            echo '<label for="' . $id . '">' . ucfirst(preg_replace('/([a-z])([A-Z])/', '$1 $2', $label)) . ':</label>';
            echo '<input type="text" name="' . $id . '" id="' . $id . '" class="datetime" ' . $mandatory . '>';
            break;

        case 'dateonly':
            echo '<label for="' . $id . '">' . ucfirst(preg_replace('/([a-z])([A-Z])/', '$1 $2', $label)) . ':</label>';
            echo '<input type="text" name="' . $id . '" id="' . $id . '" class="dateonly" ' . $mandatory . '>';
            break;

        case 'timeonly':
            echo '<label for="' . $id . '">' . ucfirst(preg_replace('/([a-z])([A-Z])/', '$1 $2', $label)) . ':</label>';
            echo '<input type="text" name="' . $id . '" id="' . $id . '" class="timeonly" ' . $mandatory . '>';
            break;
    }

    echo '</div>';
}

echo '<button type="submit">Submit</button>';
echo '</form>';
?>

</body>
</html>

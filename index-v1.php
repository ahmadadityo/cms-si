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
$formFields = [
    [
        'id' => 'namaBarang',
        'label' => 'Nama Barang',
        'type' => 'text',
        'length' => 45,
        'mandatory' => true
    ],
    [
        'id' => 'spesifikasi',
        'label' => 'Spesifikasi',
        'type' => 'textarea',
        'length' => 250,
        'mandatory' => false
    ],
    [
        'id' => 'pilihanCombo',
        'label' => 'Combo',
        'type' => 'select',
        'options' => ['A', 'B', 'C'],
        'default' => 'A',
        'mandatory' => true,
        'searchable' => true
    ],
    [
        'id' => 'pilihanRadio',
        'label' => 'Radio',
        'type' => 'radio',
        'options' => ['A', 'B'],
        'default' => 'A',
        'mandatory' => true
    ],
    [
        'id' => 'setuju',
        'label' => 'Ya, saya setuju',
        'type' => 'checkbox',
        'default' => true,
        'mandatory' => true
    ],
    [
        'id' => 'tanggalMulai',
        'label' => 'Tanggal mulai',
        'type' => 'datetime',
        'mandatory' => true
    ],
    [
        'id' => 'tanggalSelesai',
        'label' => 'Tanggal selesai',
        'type' => 'datetime',
        'mandatory' => true
    ],
    [
        'id' => 'tanggalSaja',
        'label' => 'Tanggal',
        'type' => 'dateonly',
        'mandatory' => true
    ],
    [
        'id' => 'jamSaja',
        'label' => 'Jam',
        'type' => 'timeonly',
        'mandatory' => true
    ]
];

echo '<form method="post" action="">';

foreach ($formFields as $field) {
    $id = $field['id'];
    $label = $field['label'];
    $type = $field['type'];
    $length = $field['length'] ?? '';
    $mandatory = !empty($field['mandatory']) ? 'required' : '';
    $default = $field['default'] ?? '';
    $searchable = $field['searchable'] ?? false;

    echo '<div style="margin-bottom:15px;">';

    switch ($type) {
        case 'text':
            echo '<label for="' . $label . '">' . ucfirst($label) . ':</label>';
            echo '<input type="text" name="' . $id . '" id="' . $id . '" maxlength="' . $length . '" ' . $mandatory . '>';
            break;

        case 'textarea':
            echo '<label for="' . $label . '">' . ucfirst($label) . ':</label>';
            echo '<textarea name="' . $id . '" id="' . $id . '" maxlength="' . $length . '" ' . $mandatory . '></textarea>';
            break;

        case 'select':
            echo '<label for="' . $label . '">' . ucfirst($label) . ':</label>';
            $selectClass = $searchable ? 'select2' : '';
            echo '<select name="' . $id . '" id="' . $id . '" class="' . $selectClass . '" ' . $mandatory . '>';
            echo '<option value="">-- Pilih --</option>';
            foreach ($field['options'] as $option) {
                $selected = ($option === $default) ? 'selected' : '';
                echo '<option value="' . $option . '" ' . $selected . '>' . $option . '</option>';
            }
            echo '</select>';
            break;

        case 'radio':
            echo '<label>' . ucfirst($label) . ':</label><br>';
            foreach ($field['options'] as $option) {
                $checked = ($option === $default) ? 'checked' : '';
                echo '<input type="radio" name="' . $id . '" value="' . $option . '" ' . $checked . ' ' . $mandatory . '> ' . $option . '<br>';
            }
            break;

        case 'checkbox':
            $label = $field['label'] ?? ucfirst($id);
            $checked = ($default === true) ? 'checked' : '';
            echo '<input type="checkbox" name="' . $id . '" id="' . $id . '" value="1" ' . $checked . ' ' . $mandatory . '>';
            echo '<label for="' . $label . '"> ' . $label . '</label>';
            break;

        case 'datetime':
            echo '<label for="' . $label . '">' . ucfirst(preg_replace('/([a-z])([A-Z])/', '$1 $2', $label)) . ':</label>';
            echo '<input type="text" name="' . $id . '" id="' . $id . '" class="datetime" ' . $mandatory . '>';
            break;

        case 'dateonly':
            echo '<label for="' . $label . '">' . ucfirst(preg_replace('/([a-z])([A-Z])/', '$1 $2', $label)) . ':</label>';
            echo '<input type="text" name="' . $id . '" id="' . $id . '" class="dateonly" ' . $mandatory . '>';
            break;

        case 'timeonly':
            echo '<label for="' . $label . '">' . ucfirst(preg_replace('/([a-z])([A-Z])/', '$1 $2', $label)) . ':</label>';
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

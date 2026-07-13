**edit_ملاحة.php**

<?php
// Start session
session_start();

// Check if user is logged in
if (!isset($_SESSION['logged_in'])) {
    header('Location: login.php');
    exit;
}

// Get ID from URL
$id = $_GET['id'];

// Fetch existing record details via GET
$url = '../backend/ملاحة.php?id=' . $id;
$response = file_get_contents($url);
$data = json_decode($response, true);

// Check if data exists
if (empty($data)) {
    echo 'Error: Record not found.';
    exit;
}

// Set form data
$form_data = [
    'id' => $id,
    'field1' => $data['field1'],
    'field2' => $data['field2'],
    'field3' => $data['field3'],
];

?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تعديل ملاحة</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="max-w-md mx-auto p-4 bg-white rounded-md shadow-md">
        <h1 class="text-lg font-bold text-emerald-600 mb-4">تعديل ملاحة</h1>
        <form id="edit-form" class="space-y-4">
            <div>
                <label for="field1" class="block text-sm font-medium text-gray-700">Field 1:</label>
                <input type="text" id="field1" name="field1" class="block w-full px-4 py-2 text-gray-700 border border-gray-300 rounded-md focus:ring-teal-500 focus:border-teal-500" value="<?= $form_data['field1'] ?>">
            </div>
            <div>
                <label for="field2" class="block text-sm font-medium text-gray-700">Field 2:</label>
                <input type="text" id="field2" name="field2" class="block w-full px-4 py-2 text-gray-700 border border-gray-300 rounded-md focus:ring-teal-500 focus:border-teal-500" value="<?= $form_data['field2'] ?>">
            </div>
            <div>
                <label for="field3" class="block text-sm font-medium text-gray-700">Field 3:</label>
                <input type="text" id="field3" name="field3" class="block w-full px-4 py-2 text-gray-700 border border-gray-300 rounded-md focus:ring-teal-500 focus:border-teal-500" value="<?= $form_data['field3'] ?>">
            </div>
            <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2 px-4 rounded-md">حفظ</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#edit-form').submit(function(e) {
                e.preventDefault();
                var formData = $(this).serialize();
                $.ajax({
                    type: 'PUT',
                    url: '../backend/ملاحة.php',
                    data: formData,
                    success: function(response) {
                        window.location.href = 'list_ملاحة.php';
                    },
                    error: function(xhr, status, error) {
                        console.error(xhr.responseText);
                    }
                });
            });
        });
    </script>
</body>
</html>


**Note:** Make sure to replace `../backend/ملاحة.php` with the actual URL of your backend API. Also, ensure that the `ملاحة.php` file is configured to handle PUT requests and update the record accordingly.
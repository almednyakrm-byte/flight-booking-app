**edit_ملاحة-الجوية.php**

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
$existingRecord = json_decode(file_get_contents('../backend/ملاحة-الجوية.php?id=' . $id), true);

// Check if record exists
if (!$existingRecord) {
    echo 'Record not found';
    exit;
}

?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تعديل ملاحة جوية</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="max-w-md mx-auto p-4 bg-white rounded-md shadow-md">
        <h1 class="text-lg font-bold text-emerald-600 mb-4">تعديل ملاحة جوية</h1>
        <form id="edit-form" class="space-y-4">
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700">اسم الملاحة الجوية</label>
                <input type="text" id="name" name="name" class="block w-full p-2 border-gray-300 rounded-md" value="<?php echo $existingRecord['name']; ?>">
            </div>
            <div>
                <label for="description" class="block text-sm font-medium text-gray-700">وصف الملاحة الجوية</label>
                <textarea id="description" name="description" class="block w-full p-2 border-gray-300 rounded-md" rows="4"><?php echo $existingRecord['description']; ?></textarea>
            </div>
            <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2 px-4 rounded-md">حفظ التعديلات</button>
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
                    url: '../backend/ملاحة-الجوية.php',
                    data: formData,
                    success: function(response) {
                        if (response.success) {
                            window.location.href = 'list_ملاحة_الجوية.php';
                        } else {
                            alert('Error: ' + response.message);
                        }
                    }
                });
            });
        });
    </script>
</body>
</html>


**ملاحة-الجوية.php (backend)**

<?php
// Check if ID is set
if (!isset($_GET['id'])) {
    echo json_encode(array('success' => false, 'message' => 'ID not set'));
    exit;
}

// Get ID
$id = $_GET['id'];

// Fetch existing record details from database
// Replace with your actual database query
$existingRecord = array(
    'id' => $id,
    'name' => 'ملاحة جوية',
    'description' => 'وصف الملاحة الجوية'
);

// Return existing record details as JSON
echo json_encode($existingRecord);
?>


**Note:** Replace the `ملاحة-الجوية.php` backend file with your actual database query to fetch the existing record details. Also, make sure to update the `list_ملاحة_الجوية.php` URL in the JavaScript code to point to the correct page.
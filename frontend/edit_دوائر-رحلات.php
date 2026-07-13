**edit_دوائر-رحلات.php**

<?php
session_start();

// Validate session
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

// Get ID from URL
$id = $_GET['id'];

// Fetch existing record details via GET
$url = '../backend/دوائر-رحلات.php?id=' . $id;
$response = file_get_contents($url);
$data = json_decode($response, true);

// Set form fields
$name = $data['name'];
$description = $data['description'];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit دوائر رحلات</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="max-w-md mx-auto p-4 bg-white rounded-lg shadow-md">
        <h2 class="text-lg font-bold text-indigo-500 mb-4">Edit دوائر رحلات</h2>
        <form id="edit-form" class="space-y-4">
            <div>
                <label for="name" class="block text-sm font-medium text-slate-900">Name:</label>
                <input type="text" id="name" name="name" value="<?= $name ?>" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            </div>
            <div>
                <label for="description" class="block text-sm font-medium text-slate-900">Description:</label>
                <textarea id="description" name="description" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"><?= $description ?></textarea>
            </div>
            <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-500 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">Save Changes</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#edit-form').submit(function(e) {
                e.preventDefault();
                var formData = new FormData(this);
                $.ajax({
                    type: 'PUT',
                    url: '../backend/دوائر-رحلات.php',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(data) {
                        window.location.href = 'list_<?= $_SESSION['mod_slug'] ?>.php';
                    }
                });
            });
        });
    </script>
</body>
</html>


**backend/دوائر-رحلات.php**

<?php
// Get ID from URL
$id = $_GET['id'];

// Fetch existing record details from database
// Replace with your actual database query
$data = array(
    'id' => $id,
    'name' => 'Existing Name',
    'description' => 'Existing Description'
);

// Output data in JSON format
header('Content-Type: application/json');
echo json_encode($data);
?>
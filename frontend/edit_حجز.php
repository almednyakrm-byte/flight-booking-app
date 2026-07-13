**edit_حجز.php**

<?php
// Session validation
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

// Get ID from URL
$id = $_GET['id'];

// Fetch existing record details via GET
$url = '../backend/حجز.php?id=' . $id;
$response = file_get_contents($url);
$data = json_decode($response, true);

// Check if data exists
if (empty($data)) {
    echo 'Error: Record not found';
    exit;
}

// Set form data
$form_data = $data;

// Set form fields
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit حجز</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body>
    <div class="max-w-md mx-auto p-4 bg-white rounded-lg shadow-md">
        <h2 class="text-lg font-bold mb-4">Edit حجز</h2>
        <form id="edit-form" class="space-y-4">
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                <input type="text" id="name" name="name" class="block w-full px-4 py-2 text-sm text-gray-700 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500" value="<?= $form_data['name'] ?>">
            </div>
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                <input type="email" id="email" name="email" class="block w-full px-4 py-2 text-sm text-gray-700 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500" value="<?= $form_data['email'] ?>">
            </div>
            <div>
                <label for="phone" class="block text-sm font-medium text-gray-700">Phone</label>
                <input type="tel" id="phone" name="phone" class="block w-full px-4 py-2 text-sm text-gray-700 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500" value="<?= $form_data['phone'] ?>">
            </div>
            <button type="submit" class="w-full px-4 py-2 text-sm text-white bg-blue-500 rounded-lg hover:bg-blue-700 focus:ring-blue-500 focus:border-blue-500">Update حجز</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#edit-form').submit(function(e) {
                e.preventDefault();
                var formData = $(this).serialize();
                $.ajax({
                    type: 'PUT',
                    url: '../backend/حجز.php',
                    data: formData,
                    success: function(response) {
                        if (response === 'success') {
                            window.location.href = 'list_حجز.php';
                        } else {
                            alert('Error updating record');
                        }
                    }
                });
            });
        });
    </script>
</body>
</html>

**Note:** Make sure to replace `list_حجز.php` with the actual URL of the list page. Also, ensure that the `../backend/حجز.php` file is properly configured to handle PUT requests and update the record accordingly.
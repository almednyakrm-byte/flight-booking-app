**edit_مدير-رحلات.php**

<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    header('Location: login.php');
    exit;
}

// Get ID from URL
$id = $_GET['id'];

// Fetch existing record details
$record = json_decode(file_get_contents('../backend/مدير-رحلات.php?id=' . $id), true);

?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تعديل مدير رحلات</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mx-auto p-4">
        <h1 class="text-3xl font-bold mb-4">تعديل مدير رحلات</h1>
        <form id="edit-form" class="bg-white p-4 rounded shadow-md">
            <div class="mb-4">
                <label for="name" class="block text-gray-700 text-sm font-bold mb-2">اسم المدير</label>
                <input type="text" id="name" name="name" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" value="<?php echo $record['name']; ?>">
            </div>
            <div class="mb-4">
                <label for="email" class="block text-gray-700 text-sm font-bold mb-2">البريد الإلكتروني</label>
                <input type="email" id="email" name="email" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" value="<?php echo $record['email']; ?>">
            </div>
            <div class="mb-4">
                <label for="phone" class="block text-gray-700 text-sm font-bold mb-2">رقم الهاتف</label>
                <input type="tel" id="phone" name="phone" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" value="<?php echo $record['phone']; ?>">
            </div>
            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">تعديل</button>
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
                    url: '../backend/مدير-رحلات.php',
                    data: formData,
                    success: function(response) {
                        if (response.success) {
                            window.location.href = 'list_مدير-رحلات.php';
                        } else {
                            alert('Error: ' + response.message);
                        }
                    },
                    error: function(xhr, status, error) {
                        alert('Error: ' + error);
                    }
                });
            });
        });
    </script>
</body>
</html>


**مدير-رحلات.php (backend)**

<?php
// Check if ID is set
if (!isset($_GET['id'])) {
    exit;
}

// Get ID from URL
$id = $_GET['id'];

// Fetch existing record details
$record = array();
// Assuming you have a database connection here
// $record = fetchRecordFromDatabase($id);

// Return record as JSON
header('Content-Type: application/json');
echo json_encode($record);
exit;


**مدير-رحلات.php (backend) - Update record**

<?php
// Check if ID is set
if (!isset($_GET['id'])) {
    exit;
}

// Get ID from URL
$id = $_GET['id'];

// Get form data
$name = $_POST['name'];
$email = $_POST['email'];
$phone = $_POST['phone'];

// Update record in database
// Assuming you have a database connection here
// updateRecordInDatabase($id, $name, $email, $phone);

// Return success message as JSON
header('Content-Type: application/json');
echo json_encode(array('success' => true, 'message' => 'Record updated successfully'));
exit;
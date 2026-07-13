**edit_ملاحة-الرحلات.php**

<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

// Get id from URL
$id = $_GET['id'];

// Fetch existing record details via GET
$url = '../backend/ملاحة-الرحلات.php?id=' . $id;
$response = file_get_contents($url);
$data = json_decode($response, true);

?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تعديل ملاحة الرحلات</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDzfgbWSSxoLHrNp8zContwJxQ1LS" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.4.10/dist/sweetalert2.min.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            direction: rtl;
        }
        .container {
            max-width: 800px;
            margin: 40px auto;
            padding: 20px;
            background-color: #f9f9f9;
            border: 1px solid #ddd;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-group label {
            display: block;
            margin-bottom: 10px;
        }
        .form-group input, .form-group select {
            width: 100%;
            height: 40px;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .form-group input[type="submit"] {
            background-color: #4CAF50;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .form-group input[type="submit"]:hover {
            background-color: #3e8e41;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2 class="text-emerald-600 text-2xl font-bold mb-4">تعديل ملاحة الرحلات</h2>
        <form id="edit-form">
            <div class="form-group">
                <label for="name">اسم الملاحة:</label>
                <input type="text" id="name" name="name" value="<?php echo $data['name']; ?>">
            </div>
            <div class="form-group">
                <label for="description">وصف الملاحة:</label>
                <textarea id="description" name="description"><?php echo $data['description']; ?></textarea>
            </div>
            <div class="form-group">
                <label for="image">صورة الملاحة:</label>
                <input type="file" id="image" name="image">
            </div>
            <div class="form-group">
                <input type="submit" value="حفظ التغييرات">
            </div>
        </form>
    </div>

    <script>
        $(document).ready(function() {
            // Fetch existing record details via GET
            $.ajax({
                type: 'GET',
                url: '../backend/ملاحة-الرحلات.php?id=<?php echo $id; ?>',
                dataType: 'json',
                success: function(data) {
                    $('#name').val(data.name);
                    $('#description').val(data.description);
                }
            });

            // Handle form submission
            $('#edit-form').submit(function(e) {
                e.preventDefault();
                var formData = new FormData(this);
                $.ajax({
                    type: 'PUT',
                    url: '../backend/ملاحة-الرحلات.php',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function() {
                        Swal.fire({
                            title: 'نجاح',
                            text: 'تم تعديل الملاحة بنجاح',
                            icon: 'success',
                            confirmButtonText: 'حسناً'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.href = 'list_ملاحة-الرحلات.php';
                            }
                        });
                    },
                    error: function(xhr, status, error) {
                        Swal.fire({
                            title: 'خطأ',
                            text: 'حدث خطأ أثناء تعديل الملاحة',
                            icon: 'error',
                            confirmButtonText: 'حسناً'
                        });
                    }
                });
            });
        });
    </script>
</body>
</html>


**ملاحة-الرحلات.php (backend)**

<?php
// Check if id is set
if (!isset($_GET['id'])) {
    exit;
}

// Connect to database
$conn = mysqli_connect('localhost', 'username', 'password', 'database');

// Check connection
if (!$conn) {
    die('خطأ في الاتصال بالبيانات');
}

// Get id from URL
$id = $_GET['id'];

// Fetch existing record details
$query = "SELECT * FROM ملاحة_الرحلات WHERE id = '$id'";
$result = mysqli_query($conn, $query);

// Check if record exists
if (mysqli_num_rows($result) == 0) {
    exit;
}

// Get record details
$data = mysqli_fetch_assoc($result);

// Close connection
mysqli_close($conn);

// Output record details as JSON
echo json_encode($data);
?>


Note: This code assumes that you have a database table named `ملاحة_الرحلات` with columns `id`, `name`, `description`, and `image`. You should replace the database credentials and table name with your own. Additionally, this code uses a simple file upload mechanism and does not include any validation or security measures. You should consider implementing these features in a production environment.
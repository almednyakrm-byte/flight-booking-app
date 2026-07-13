**create_مدير-رحلات.php**

<?php
// Session validation
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

// Include header and navigation
include 'header.php';
include 'navigation.php';
?>

<div class="container mx-auto p-4 pt-6">
    <div class="bg-white rounded shadow-md p-4">
        <h2 class="text-lg font-bold mb-4">إضافة مدير رحلات جديد</h2>
        <form id="create-form" method="post">
            <div class="mb-4">
                <label for="name" class="block text-sm font-bold mb-2">اسم المدير</label>
                <input type="text" id="name" name="name" class="block w-full p-2 border border-gray-300 rounded" required>
            </div>
            <div class="mb-4">
                <label for="email" class="block text-sm font-bold mb-2">بريد إلكتروني</label>
                <input type="email" id="email" name="email" class="block w-full p-2 border border-gray-300 rounded" required>
            </div>
            <div class="mb-4">
                <label for="phone" class="block text-sm font-bold mb-2">رقم الهاتف</label>
                <input type="tel" id="phone" name="phone" class="block w-full p-2 border border-gray-300 rounded" required>
            </div>
            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">حفظ</button>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
<script>
    $(document).ready(function() {
        $('#create-form').submit(function(e) {
            e.preventDefault();
            var formData = $(this).serialize();
            $.ajax({
                type: 'POST',
                url: '../backend/مدير-رحلات.php',
                data: formData,
                success: function(response) {
                    if (response == 'success') {
                        window.location.href = 'list_مدير-رحلات.php';
                    } else {
                        alert('حدث خطأ أثناء الحفظ');
                    }
                }
            });
        });
    });
</script>

<?php
// Include footer
include 'footer.php';
?>


**مدير-رحلات.php (backend)**

<?php
// Check if form data is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Database connection
    $conn = mysqli_connect('localhost', 'username', 'password', 'database');
    if (!$conn) {
        die('Failed to connect to database: ' . mysqli_connect_error());
    }

    // Prepare SQL query
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $sql = "INSERT INTO مدير_رحلات (name, email, phone) VALUES ('$name', '$email', '$phone')";

    // Execute query
    if (mysqli_query($conn, $sql)) {
        echo 'success';
    } else {
        echo 'error';
    }

    // Close database connection
    mysqli_close($conn);
}
?>


Note: This code assumes you have a database table named `مدير_رحلات` with columns `name`, `email`, and `phone`. You should replace the database connection credentials and table name with your actual values. Additionally, this code does not include any validation or sanitization of user input, which is a security risk. You should add proper validation and sanitization to prevent SQL injection and other security vulnerabilities.
**create_ملاحة.php**

<?php
// Session validation
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Include header and navigation
require_once 'header.php';
?>

<div class="container mx-auto p-4 pt-6 md:p-6 lg:p-12 xl:p-12">
    <div class="bg-white rounded-lg shadow-md p-4 md:p-6 lg:p-8 xl:p-8">
        <h2 class="text-lg font-bold text-emerald-600 mb-4">إضافة جديد</h2>
        <form id="create-form" class="space-y-4">
            <div class="flex flex-wrap -mx-3 mb-6">
                <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
                    <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="name">
                        الاسم
                    </label>
                    <input class="appearance-none block w-full bg-white text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="name" type="text" required>
                </div>
                <div class="w-full md:w-1/2 px-3">
                    <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="description">
                        الوصف
                    </label>
                    <textarea class="appearance-none block w-full bg-white text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="description" rows="4" required></textarea>
                </div>
            </div>
            <div class="flex flex-wrap -mx-3 mb-6">
                <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
                    <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="location">
                        الموقع
                    </label>
                    <input class="appearance-none block w-full bg-white text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="location" type="text" required>
                </div>
                <div class="w-full md:w-1/2 px-3">
                    <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="contact">
                        الإتصال
                    </label>
                    <input class="appearance-none block w-full bg-white text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="contact" type="text" required>
                </div>
            </div>
            <button type="submit" class="bg-teal-500 hover:bg-teal-700 text-white font-bold py-2 px-4 rounded">إضافة</button>
        </form>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#create-form').submit(function(e) {
            e.preventDefault();
            $.ajax({
                type: 'POST',
                url: '../backend/ملاحة.php',
                data: $(this).serialize(),
                success: function(response) {
                    if (response === 'success') {
                        window.location.href = 'list_ملاحة.php';
                    } else {
                        alert('Error: ' + response);
                    }
                }
            });
        });
    });
</script>

<?php
// Include footer
require_once 'footer.php';
?>


**ملاحة.php (backend)**

<?php
// Database connection
require_once 'db.php';

// Check if form data is sent
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Extract form data
    $name = $_POST['name'];
    $description = $_POST['description'];
    $location = $_POST['location'];
    $contact = $_POST['contact'];

    // Insert data into database
    $query = "INSERT INTO ملاحة (name, description, location, contact) VALUES ('$name', '$description', '$location', '$contact')";
    $result = mysqli_query($conn, $query);

    // Check if data is inserted successfully
    if ($result) {
        echo 'success';
    } else {
        echo 'Error: ' . mysqli_error($conn);
    }
}

// Close database connection
mysqli_close($conn);
?>
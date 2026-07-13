**create_ملاحة-الجوية.php**

<?php
// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Include header and navigation
include 'header.php';
include 'navigation.php';

// Include form validation library
include 'form_validation.php';

// Define form validation rules
$validation_rules = array(
    'name' => 'required',
    'description' => 'required',
    'location' => 'required',
    'altitude' => 'required|numeric',
    'speed' => 'required|numeric',
    'course' => 'required|numeric',
);

// Validate form data
if (isset($_POST['submit'])) {
    $validation = new FormValidation();
    $validation->set_rules($validation_rules);
    $validation->run($_POST);

    if ($validation->passed()) {
        // Process form data
        $name = $_POST['name'];
        $description = $_POST['description'];
        $location = $_POST['location'];
        $altitude = $_POST['altitude'];
        $speed = $_POST['speed'];
        $course = $_POST['course'];

        // Insert data into database
        $db = new Database();
        $db->query("INSERT INTO ملاحة_الجوية (name, description, location, altitude, speed, course) VALUES (:name, :description, :location, :altitude, :speed, :course)");
        $db->bind(':name', $name);
        $db->bind(':description', $description);
        $db->bind(':location', $location);
        $db->bind(':altitude', $altitude);
        $db->bind(':speed', $speed);
        $db->bind(':course', $course);
        $db->execute();

        // Redirect to list page
        header('Location: list_ملاحة-الجوية.php');
        exit;
    } else {
        // Display validation errors
        $errors = $validation->errors();
    }
}

// Include form view
include 'create_ملاحة-الجوية_form.php';
?>

<?php
// Include footer
include 'footer.php';
?>


**create_ملاحة-الجوية_form.php**

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 bg-white border-b border-gray-200">
            <h2 class="text-lg leading-6 font-medium text-gray-900">
                Create New ملاحة_الجوية
            </h2>
            <form id="create-form" method="post" action="">
                <div class="mt-6">
                    <div class="grid grid-cols-1 gap-6">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                            <input type="text" name="name" id="name" class="mt-1 focus:ring-emerald-600 focus:border-emerald-600 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" required>
                        </div>
                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                            <input type="text" name="description" id="description" class="mt-1 focus:ring-emerald-600 focus:border-emerald-600 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" required>
                        </div>
                        <div>
                            <label for="location" class="block text-sm font-medium text-gray-700">Location</label>
                            <input type="text" name="location" id="location" class="mt-1 focus:ring-emerald-600 focus:border-emerald-600 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" required>
                        </div>
                        <div>
                            <label for="altitude" class="block text-sm font-medium text-gray-700">Altitude</label>
                            <input type="number" name="altitude" id="altitude" class="mt-1 focus:ring-emerald-600 focus:border-emerald-600 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" required>
                        </div>
                        <div>
                            <label for="speed" class="block text-sm font-medium text-gray-700">Speed</label>
                            <input type="number" name="speed" id="speed" class="mt-1 focus:ring-emerald-600 focus:border-emerald-600 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" required>
                        </div>
                        <div>
                            <label for="course" class="block text-sm font-medium text-gray-700">Course</label>
                            <input type="number" name="course" id="course" class="mt-1 focus:ring-emerald-600 focus:border-emerald-600 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" required>
                        </div>
                    </div>
                </div>
                <div class="mt-6">
                    <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-emerald-600 hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500">Create</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#create-form').submit(function(event) {
            event.preventDefault();
            $.ajax({
                type: 'POST',
                url: '../backend/ملاحة-الجوية.php',
                data: $(this).serialize(),
                success: function(data) {
                    if (data == 'success') {
                        window.location.href = 'list_ملاحة-الجوية.php';
                    } else {
                        alert('Error creating ملاحة_الجوية');
                    }
                }
            });
        });
    });
</script>


**footer.php**

</body>
</html>


**header.php**

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ملاحة_الجوية</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@5.15.3/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .bg-emerald-600 {
            background-color: #34C759;
        }
        .text-emerald-600 {
            color: #34C759;
        }
        .bg-teal-500 {
            background-color: #0097A7;
        }
        .text-teal-500 {
            color: #0097A7;
        }
    </style>
</head>
<body>
    <nav class="bg-teal-500 py-2">
        <div class="container mx-auto px-4 flex justify-between items-center">
            <a href="#" class="text-teal-500 text-lg font-bold">ملاحة_الجوية</a>
            <ul class="flex items-center space-x-4">
                <li><a href="#" class="text-teal-500 hover:text-white">Home</a></li>
                <li><a href="#" class="text-teal-500 hover:text-white">About</a></li>
                <li><a href="#" class="text-teal-500 hover:text-white">Contact</a></li>
            </ul>
        </div>
    </nav>


**navigation.php**

<nav class="bg-white py-2">
    <div class="container mx-auto px-4 flex justify-between items-center">
        <ul class="flex items-center space-x-4">
            <li><a href="#" class="text-gray-700 hover:text-gray-900">Dashboard</a></li>
            <li><a href="#" class="text-gray-700 hover:text-gray-900">Settings</a></li>
            <li><a href="#" class="text-gray-700 hover:text-gray-900">Help</a></li>
        </ul>
        <ul class="flex items-center space-x-4">
            <li><a href="#" class="text-gray-700 hover:text-gray-900">Login</a></li>
            <li><a href="#" class="text-gray-700 hover:text-gray-900">Register</a></li>
        </ul>
    </div>
</nav>
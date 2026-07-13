**create_ملاحة-الرحلات.php**

<?php
// Start session
session_start();

// Check if user is logged in
if (!isset($_SESSION['logged_in'])) {
    header('Location: login.php');
    exit;
}

// Include database connection
require_once '../config/db.php';

// Check if form has been submitted
if (isset($_POST['submit'])) {
    // Validate form data
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);
    $date = trim($_POST['date']);
    $time = trim($_POST['time']);
    $location = trim($_POST['location']);

    // Validate fields
    if (empty($name) || empty($description) || empty($date) || empty($time) || empty($location)) {
        $error = 'Please fill in all fields';
    } else {
        // Insert data into database
        $sql = "INSERT INTO ملاحة_الرحلات (name, description, date, time, location) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssss", $name, $description, $date, $time, $location);
        if ($stmt->execute()) {
            // Redirect back to list page
            header('Location: list_ملاحة-الرحلات.php');
            exit;
        } else {
            $error = 'Error inserting data';
        }
    }
}

// Include header
require_once '../includes/header.php';

?>

<!-- Create form -->
<div class="max-w-md mx-auto p-4 bg-white rounded-lg shadow-md">
    <h2 class="text-lg font-bold text-emerald-600 mb-4">Create New ملاحة_الرحلات</h2>
    <form id="create-form" method="POST">
        <div class="mb-4">
            <label for="name" class="block text-sm font-medium text-gray-700">Name:</label>
            <input type="text" id="name" name="name" class="block w-full px-4 py-2 text-gray-700 border border-gray-300 rounded-lg focus:ring-teal-500 focus:border-teal-500" required>
        </div>
        <div class="mb-4">
            <label for="description" class="block text-sm font-medium text-gray-700">Description:</label>
            <textarea id="description" name="description" class="block w-full px-4 py-2 text-gray-700 border border-gray-300 rounded-lg focus:ring-teal-500 focus:border-teal-500" required></textarea>
        </div>
        <div class="mb-4">
            <label for="date" class="block text-sm font-medium text-gray-700">Date:</label>
            <input type="date" id="date" name="date" class="block w-full px-4 py-2 text-gray-700 border border-gray-300 rounded-lg focus:ring-teal-500 focus:border-teal-500" required>
        </div>
        <div class="mb-4">
            <label for="time" class="block text-sm font-medium text-gray-700">Time:</label>
            <input type="time" id="time" name="time" class="block w-full px-4 py-2 text-gray-700 border border-gray-300 rounded-lg focus:ring-teal-500 focus:border-teal-500" required>
        </div>
        <div class="mb-4">
            <label for="location" class="block text-sm font-medium text-gray-700">Location:</label>
            <input type="text" id="location" name="location" class="block w-full px-4 py-2 text-gray-700 border border-gray-300 rounded-lg focus:ring-teal-500 focus:border-teal-500" required>
        </div>
        <button type="submit" name="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2 px-4 rounded-lg">Create</button>
    </form>
</div>

<!-- Include footer -->
<?php require_once '../includes/footer.php'; ?>

<script>
    // AJAX form submission
    document.getElementById('create-form').addEventListener('submit', function(event) {
        event.preventDefault();
        var formData = new FormData(this);
        $.ajax({
            type: 'POST',
            url: '../backend/ملاحة-الرحلات.php',
            data: formData,
            processData: false,
            contentType: false,
            success: function(data) {
                if (data === 'success') {
                    window.location.href = 'list_ملاحة-الرحلات.php';
                } else {
                    alert('Error creating ملاحة_الرحلات');
                }
            }
        });
    });
</script>

**Note:** Make sure to replace `../backend/ملاحة-الرحلات.php` with the actual PHP file that handles the form submission and database insertion. Also, update the `list_ملاحة-الرحلات.php` file to handle the redirect after successful form submission.
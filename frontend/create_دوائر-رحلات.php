**create_دوائر-رحلات.php**

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
    $start_date = trim($_POST['start_date']);
    $end_date = trim($_POST['end_date']);
    $location = trim($_POST['location']);

    // Check for empty fields
    if (empty($name) || empty($description) || empty($start_date) || empty($end_date) || empty($location)) {
        $error = 'Please fill in all fields';
    } else {
        // Insert data into database
        $query = "INSERT INTO دوائر_رحلات (name, description, start_date, end_date, location) VALUES ('$name', '$description', '$start_date', '$end_date', '$location')";
        $result = mysqli_query($conn, $query);

        if ($result) {
            // Redirect back to list page
            header('Location: list_دوائر-رحلات.php');
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
    <h2 class="text-lg font-bold text-indigo-500 mb-4">Create New دوائر_رحلات</h2>
    <form id="create-form" method="post">
        <div class="mb-4">
            <label for="name" class="block text-sm font-medium text-slate-900">Name:</label>
            <input type="text" id="name" name="name" class="block w-full p-2 mt-1 text-sm text-slate-900 border border-slate-300 rounded-lg" required>
        </div>
        <div class="mb-4">
            <label for="description" class="block text-sm font-medium text-slate-900">Description:</label>
            <textarea id="description" name="description" class="block w-full p-2 mt-1 text-sm text-slate-900 border border-slate-300 rounded-lg" required></textarea>
        </div>
        <div class="mb-4">
            <label for="start_date" class="block text-sm font-medium text-slate-900">Start Date:</label>
            <input type="date" id="start_date" name="start_date" class="block w-full p-2 mt-1 text-sm text-slate-900 border border-slate-300 rounded-lg" required>
        </div>
        <div class="mb-4">
            <label for="end_date" class="block text-sm font-medium text-slate-900">End Date:</label>
            <input type="date" id="end_date" name="end_date" class="block w-full p-2 mt-1 text-sm text-slate-900 border border-slate-300 rounded-lg" required>
        </div>
        <div class="mb-4">
            <label for="location" class="block text-sm font-medium text-slate-900">Location:</label>
            <input type="text" id="location" name="location" class="block w-full p-2 mt-1 text-sm text-slate-900 border border-slate-300 rounded-lg" required>
        </div>
        <button type="submit" name="submit" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">Create</button>
    </form>
    <?php if (isset($error)) : ?>
        <p class="text-red-500 mt-2"><?= $error ?></p>
    <?php endif; ?>
</div>

<!-- Include footer -->
<?php require_once '../includes/footer.php'; ?>

<script>
    // AJAX form submission
    document.getElementById('create-form').addEventListener('submit', function(event) {
        event.preventDefault();
        var formData = new FormData(this);
        fetch('../backend/دوائر-رحلات.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                window.location.href = 'list_دوائر-رحلات.php';
            } else {
                console.error(data.error);
            }
        })
        .catch(error => console.error(error));
    });
</script>

**backend/دوائر-رحلات.php**

<?php
// Include database connection
require_once '../config/db.php';

// Check if form data has been sent
if (isset($_POST['name']) && isset($_POST['description']) && isset($_POST['start_date']) && isset($_POST['end_date']) && isset($_POST['location'])) {
    // Insert data into database
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);
    $start_date = trim($_POST['start_date']);
    $end_date = trim($_POST['end_date']);
    $location = trim($_POST['location']);

    $query = "INSERT INTO دوائر_رحلات (name, description, start_date, end_date, location) VALUES ('$name', '$description', '$start_date', '$end_date', '$location')";
    $result = mysqli_query($conn, $query);

    if ($result) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['error' => 'Error inserting data']);
    }
} else {
    echo json_encode(['error' => 'Invalid form data']);
}
?>
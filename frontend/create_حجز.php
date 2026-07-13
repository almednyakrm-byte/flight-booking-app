**create_حجز.php**

<?php
// Start session
session_start();

// Check if user is logged in
if (!isset($_SESSION['logged_in'])) {
    header('Location: login.php');
    exit;
}

// Include database connection
require_once '../backend/db.php';

// Check if form has been submitted
if (isset($_POST['submit'])) {
    // Validate form data
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $date = trim($_POST['date']);
    $time = trim($_POST['time']);
    $guests = trim($_POST['guests']);

    // Check for empty fields
    if (empty($name) || empty($email) || empty($phone) || empty($date) || empty($time) || empty($guests)) {
        $error = 'Please fill in all fields';
    } else {
        // Insert data into database
        $sql = "INSERT INTO حجز (name, email, phone, date, time, guests) VALUES ('$name', '$email', '$phone', '$date', '$time', '$guests')";
        $result = mysqli_query($conn, $sql);

        if ($result) {
            // Redirect back to list_{mod_slug}.php
            header('Location: list_حجز.php');
            exit;
        } else {
            $error = 'Error inserting data';
        }
    }
}

// Include header
require_once '../backend/header.php';

?>

<!-- Create new حجز form -->
<div class="max-w-md mx-auto p-4 bg-white rounded-lg shadow-md">
    <h2 class="text-lg font-bold mb-4">Create New حجز</h2>
    <form id="create-form" method="post">
        <div class="mb-4">
            <label for="name" class="block text-sm font-bold mb-2">Name:</label>
            <input type="text" id="name" name="name" class="block w-full px-4 py-2 text-sm text-gray-700" placeholder="John Doe">
        </div>
        <div class="mb-4">
            <label for="email" class="block text-sm font-bold mb-2">Email:</label>
            <input type="email" id="email" name="email" class="block w-full px-4 py-2 text-sm text-gray-700" placeholder="john.doe@example.com">
        </div>
        <div class="mb-4">
            <label for="phone" class="block text-sm font-bold mb-2">Phone:</label>
            <input type="tel" id="phone" name="phone" class="block w-full px-4 py-2 text-sm text-gray-700" placeholder="+1234567890">
        </div>
        <div class="mb-4">
            <label for="date" class="block text-sm font-bold mb-2">Date:</label>
            <input type="date" id="date" name="date" class="block w-full px-4 py-2 text-sm text-gray-700">
        </div>
        <div class="mb-4">
            <label for="time" class="block text-sm font-bold mb-2">Time:</label>
            <input type="time" id="time" name="time" class="block w-full px-4 py-2 text-sm text-gray-700">
        </div>
        <div class="mb-4">
            <label for="guests" class="block text-sm font-bold mb-2">Guests:</label>
            <input type="number" id="guests" name="guests" class="block w-full px-4 py-2 text-sm text-gray-700" placeholder="1">
        </div>
        <button type="submit" name="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Create حجز</button>
    </form>
</div>

<!-- Include footer -->
<?php require_once '../backend/footer.php'; ?>

<script>
    // Send form data to backend using AJAX
    document.getElementById('create-form').addEventListener('submit', function(event) {
        event.preventDefault();
        var formData = new FormData(this);
        fetch('../backend/حجز.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                window.location.href = 'list_حجز.php';
            } else {
                alert(data.error);
            }
        })
        .catch(error => console.error(error));
    });
</script>


**Note:** Make sure to replace `../backend/db.php` and `../backend/header.php` and `../backend/footer.php` with the actual paths to your database connection file and header/footer files. Also, replace `../backend/حجز.php` with the actual path to your backend file that handles the form submission.
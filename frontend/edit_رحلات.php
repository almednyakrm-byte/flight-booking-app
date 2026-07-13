**edit_رحلات.php**

<?php
// Start session
session_start();

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

// Get ID from URL
$id = $_GET['id'];

// Fetch existing record details
$data = json_decode(file_get_contents('../backend/رحلات.php?id=' . $id), true);

// Check if record exists
if (empty($data)) {
    echo 'Record not found';
    exit;
}

// Set page title and mod slug
$page_title = 'Edit رحلات';
$mod_slug = 'رحلات';

// Include header
include 'header.php';
?>

<!-- Main content -->
<div class="container mx-auto p-4 pt-6 md:p-6 lg:p-12 xl:p-12 2xl:p-12">
    <div class="bg-white rounded-lg shadow-md p-4 md:p-6 lg:p-8 xl:p-8 2xl:p-8">
        <h2 class="text-lg font-bold text-emerald-600 mb-4"><?= $page_title ?></h2>
        <form id="edit-form" class="space-y-4">
            <div>
                <label for="title" class="block text-sm font-medium text-gray-700">Title</label>
                <input type="text" id="title" name="title" class="block w-full px-4 py-2 text-gray-700 bg-white border border-gray-300 rounded-md focus:outline-none focus:ring focus:ring-emerald-600 focus:border-emerald-600" value="<?= $data['title'] ?>">
            </div>
            <div>
                <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                <textarea id="description" name="description" class="block w-full px-4 py-2 text-gray-700 bg-white border border-gray-300 rounded-md focus:outline-none focus:ring focus:ring-emerald-600 focus:border-emerald-600"><?= $data['description'] ?></textarea>
            </div>
            <button type="submit" class="bg-teal-500 hover:bg-teal-700 text-white font-bold py-2 px-4 rounded">Save Changes</button>
        </form>
    </div>
</div>

<!-- JavaScript -->
<script>
    // Fetch existing record details via GET
    fetch('../backend/رحلات.php?id=' + <?= $id ?>)
        .then(response => response.json())
        .then(data => {
            // Populate form fields
            document.getElementById('title').value = data.title;
            document.getElementById('description').value = data.description;
        })
        .catch(error => console.error(error));

    // Handle form submission
    document.getElementById('edit-form').addEventListener('submit', event => {
        event.preventDefault();
        // Send AJAX PUT request
        fetch('../backend/رحلات.php', {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                id: <?= $id ?>,
                title: document.getElementById('title').value,
                description: document.getElementById('description').value
            })
        })
            .then(response => response.json())
            .then(data => {
                // Redirect to list page on success
                window.location.href = 'list_<?= $mod_slug ?>.php';
            })
            .catch(error => console.error(error));
    });
</script>

<?php
// Include footer
include 'footer.php';
?>


**backend/رحلات.php**

<?php
// Check if ID is set
if (!isset($_GET['id'])) {
    echo 'ID not set';
    exit;
}

// Connect to database
$conn = new PDO('mysql:host=localhost;dbname=database', 'username', 'password');

// Fetch existing record details
$stmt = $conn->prepare('SELECT * FROM رحلات WHERE id = :id');
$stmt->bindParam(':id', $_GET['id']);
$stmt->execute();
$data = $stmt->fetch();

// Return JSON data
echo json_encode($data);

// Close database connection
$conn = null;
?>


**Note:** This code assumes you have a database connection set up and a table named `رحلات` with columns `id`, `title`, and `description`. You'll need to replace the database connection details and table name with your own. Additionally, this code uses a simple form validation and does not include any error handling or security measures. You should consider adding these features to your production code.
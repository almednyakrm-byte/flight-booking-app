**edit_خصومات.php**

<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Get ID from URL
$id = $_GET['id'];

// Fetch existing record details via GET
$existingRecord = json_decode(file_get_contents('../backend/خصومات.php?id=' . $id), true);

// Check if record exists
if (empty($existingRecord)) {
    echo 'Record not found';
    exit;
}

// Set page title and mod_slug
$pageTitle = 'Edit خصومات';
$modSlug = 'خصومات';

// Include header and navigation
include 'header.php';
include 'navigation.php';
?>

<div class="container mx-auto p-4 pt-6 md:p-6 lg:p-12 xl:p-12">
    <div class="bg-white rounded-lg shadow-md p-4 md:p-6 lg:p-8 xl:p-8">
        <h2 class="text-lg font-bold mb-4"><?= $pageTitle ?></h2>
        <form id="edit-form" class="space-y-4">
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                <input type="text" id="name" name="name" class="block w-full px-4 py-2 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500" value="<?= $existingRecord['name'] ?>">
            </div>
            <div>
                <label for="discount" class="block text-sm font-medium text-gray-700">Discount</label>
                <input type="number" id="discount" name="discount" class="block w-full px-4 py-2 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500" value="<?= $existingRecord['discount'] ?>">
            </div>
            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Update</button>
        </form>
    </div>
</div>

<script>
    // Fetch existing record details via GET
    fetch('../backend/خصومات.php?id=' + <?= $id ?>)
        .then(response => response.json())
        .then(data => {
            // Populate form fields
            document.getElementById('name').value = data.name;
            document.getElementById('discount').value = data.discount;
        })
        .catch(error => console.error(error));

    // Handle form submission
    document.getElementById('edit-form').addEventListener('submit', function(event) {
        event.preventDefault();

        // Get form data
        const formData = new FormData(this);

        // Send PUT request to backend
        fetch('../backend/خصومات.php', {
            method: 'PUT',
            body: formData
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Redirect to list page
                    window.location.href = 'list_' + 'خصومات' + '.php';
                } else {
                    console.error(data.error);
                }
            })
            .catch(error => console.error(error));
    });
</script>

<?php
// Include footer
include 'footer.php';
?>


**backend/خصومات.php**

<?php
// Check if ID is set
if (!isset($_GET['id'])) {
    echo json_encode(['error' => 'ID not set']);
    exit;
}

// Get ID
$id = $_GET['id'];

// Check if record exists
$record = get_record($id);

if (empty($record)) {
    echo json_encode(['error' => 'Record not found']);
    exit;
}

// Update record
update_record($id, $_POST);

// Output updated record
echo json_encode($record);

function get_record($id) {
    // Database query to get record
    // ...
}

function update_record($id, $data) {
    // Database query to update record
    // ...
}
?>
**create_خصومات.php**

<?php
// Session validation
if (!isset($_SESSION['admin'])) {
    header('Location: login.php');
    exit;
}

// Include header and navigation
require_once 'header.php';
require_once 'nav.php';

// Form validation and processing
if (isset($_POST['submit'])) {
    // Validate form data
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);
    $discount_percentage = trim($_POST['discount_percentage']);

    if (!empty($name) && !empty($description) && !empty($discount_percentage)) {
        // Process form data
        $data = array(
            'name' => $name,
            'description' => $description,
            'discount_percentage' => $discount_percentage
        );

        // AJAX request to create new record
        $ajax_url = '../backend/خصومات.php';
        $ajax_data = array(
            'action' => 'create',
            'data' => $data
        );

        // Send AJAX request
        $response = send_ajax_request($ajax_url, $ajax_data);

        // Handle response
        if ($response['success']) {
            // Redirect back to list page
            header('Location: list_خصومات.php');
            exit;
        } else {
            // Display error message
            echo '<div class="alert alert-danger">' . $response['message'] . '</div>';
        }
    } else {
        // Display error message
        echo '<div class="alert alert-danger">Please fill in all required fields.</div>';
    }
}

// Send AJAX request function
function send_ajax_request($url, $data) {
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
    $response = curl_exec($ch);
    curl_close($ch);
    return json_decode($response, true);
}

// Form HTML
?>

<div class="container mx-auto p-4">
    <div class="bg-white rounded-lg shadow-md p-4">
        <h2 class="text-lg font-bold mb-4">Create New خصومات</h2>
        <form id="create-form" method="post">
            <div class="mb-4">
                <label for="name" class="block text-sm font-bold mb-2">Name:</label>
                <input type="text" id="name" name="name" class="w-full p-2 border border-gray-400 rounded-lg focus:outline-none focus:ring focus:border-blue-500" required>
            </div>
            <div class="mb-4">
                <label for="description" class="block text-sm font-bold mb-2">Description:</label>
                <textarea id="description" name="description" class="w-full p-2 border border-gray-400 rounded-lg focus:outline-none focus:ring focus:border-blue-500" required></textarea>
            </div>
            <div class="mb-4">
                <label for="discount_percentage" class="block text-sm font-bold mb-2">Discount Percentage:</label>
                <input type="number" id="discount_percentage" name="discount_percentage" class="w-full p-2 border border-gray-400 rounded-lg focus:outline-none focus:ring focus:border-blue-500" required>
            </div>
            <button type="submit" name="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg">Create</button>
        </form>
    </div>
</div>

<script>
    // Submit form via AJAX
    document.getElementById('create-form').addEventListener('submit', function(event) {
        event.preventDefault();
        var formData = new FormData(this);
        var xhr = new XMLHttpRequest();
        xhr.open('POST', '../backend/خصومات.php', true);
        xhr.onload = function() {
            if (xhr.status === 200) {
                // Redirect back to list page
                window.location.href = 'list_خصومات.php';
            } else {
                // Display error message
                var response = JSON.parse(xhr.responseText);
                alert(response.message);
            }
        };
        xhr.send(formData);
    });
</script>

<?php
// Include footer
require_once 'footer.php';
?>


**backend/خصومات.php**

<?php
// Database connection
require_once 'db.php';

// Check if request is AJAX
if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
    // Process AJAX request
    $action = $_POST['action'];
    $data = $_POST['data'];

    if ($action == 'create') {
        // Create new record
        $query = "INSERT INTO خصومات (name, description, discount_percentage) VALUES (:name, :description, :discount_percentage)";
        $stmt = $pdo->prepare($query);
        $stmt->execute(array(
            ':name' => $data['name'],
            ':description' => $data['description'],
            ':discount_percentage' => $data['discount_percentage']
        ));

        // Return success response
        echo json_encode(array('success' => true, 'message' => 'Record created successfully.'));
    }
}
?>
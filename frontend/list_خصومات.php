**list_خصومات.php**

<?php
// Session validation
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>خصومات</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body>
    <header class="bg-gray-800 text-white p-4">
        <nav class="container mx-auto flex justify-between">
            <a href="index.php" class="text-lg font-bold">Home</a>
            <div class="flex items-center">
                <p class="mr-2">Welcome, <?= $_SESSION['username'] ?></p>
                <a href="logout.php" class="text-red-500 hover:text-red-700">Logout</a>
            </div>
        </nav>
    </header>
    <main class="container mx-auto p-4">
        <h1 class="text-3xl font-bold mb-4">خصومات</h1>
        <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" onclick="location.href='create_خصومات.php'">Add New Item</button>
        <div class="flex justify-between mt-4">
            <input type="search" id="search" class="w-full p-2 mr-2" placeholder="Search...">
            <button class="bg-gray-200 hover:bg-gray-300 text-gray-600 font-bold py-2 px-4 rounded" onclick="searchRecords()">Search</button>
        </div>
        <table class="w-full mt-4">
            <thead>
                <tr>
                    <th class="px-4 py-2">ID</th>
                    <th class="px-4 py-2">Name</th>
                    <th class="px-4 py-2">Actions</th>
                </tr>
            </thead>
            <tbody id="records">
                <?php
                // Fetch records from backend
                $response = file_get_contents('../backend/خصومات.php');
                $records = json_decode($response, true);
                foreach ($records as $record) {
                    ?>
                    <tr>
                        <td class="px-4 py-2"><?= $record['id'] ?></td>
                        <td class="px-4 py-2"><?= $record['name'] ?></td>
                        <td class="px-4 py-2">
                            <a href="edit_خصومات.php?id=<?= $record['id'] ?>" class="text-blue-500 hover:text-blue-700">Edit</a>
                            <button class="text-red-500 hover:text-red-700" onclick="deleteRecord(<?= $record['id'] ?>)">Delete</button>
                        </td>
                    </tr>
                    <?php
                }
                ?>
            </tbody>
        </table>
    </main>
    <script>
        function searchRecords() {
            const searchValue = document.getElementById('search').value;
            fetch('../backend/خصومات.php?search=' + searchValue)
                .then(response => response.json())
                .then(data => {
                    const records = document.getElementById('records');
                    records.innerHTML = '';
                    data.forEach(record => {
                        const row = document.createElement('tr');
                        row.innerHTML = `
                            <td class="px-4 py-2">${record.id}</td>
                            <td class="px-4 py-2">${record.name}</td>
                            <td class="px-4 py-2">
                                <a href="edit_خصومات.php?id=${record.id}" class="text-blue-500 hover:text-blue-700">Edit</a>
                                <button class="text-red-500 hover:text-red-700" onclick="deleteRecord(${record.id})">Delete</button>
                            </td>
                        `;
                        records.appendChild(row);
                    });
                });
        }

        function deleteRecord(id) {
            if (confirm('Are you sure you want to delete this record?')) {
                fetch('../backend/خصومات.php', {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ id: id })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Record deleted successfully!');
                        window.location.reload();
                    } else {
                        alert('Error deleting record!');
                    }
                });
            }
        }
    </script>
</body>
</html>

**backend/خصومات.php**

<?php
// Check if search query is set
if (isset($_GET['search'])) {
    $searchValue = $_GET['search'];
    $records = array_filter($GLOBALS['records'], function($record) use ($searchValue) {
        return strpos($record['name'], $searchValue) !== false;
    });
} else {
    $records = $GLOBALS['records'];
}

// Return records as JSON
header('Content-Type: application/json');
echo json_encode($records);

Note: This code assumes that you have a `$records` array defined in the backend script that contains the data for the table. You will need to replace this with your actual data source.
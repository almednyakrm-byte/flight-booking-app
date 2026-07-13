**list_حجز.php**

<?php
session_start();

// Check if user is authenticated
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>حجز</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body>
    <header class="bg-gray-800 text-white p-4">
        <nav class="container mx-auto flex justify-between">
            <a href="index.php" class="text-lg font-bold">الرئيسية</a>
            <div class="flex items-center">
                <span class="text-lg font-bold"><?= $_SESSION['username'] ?></span>
                <button class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded ml-4" onclick="document.location='logout.php'">تسجيل الخروج</button>
            </div>
        </nav>
    </header>
    <main class="container mx-auto p-4">
        <h1 class="text-3xl font-bold mb-4">حجز</h1>
        <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mb-4" onclick="document.location='create_حجز.php'">إضافة جديد</button>
        <div class="flex justify-between mb-4">
            <input type="search" class="w-full p-2 border border-gray-400 rounded" placeholder="بحث" id="search">
            <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" onclick="searchRecords()">بحث</button>
        </div>
        <table class="w-full border-collapse border border-gray-400">
            <thead>
                <tr>
                    <th class="border border-gray-400 p-2">اسم</th>
                    <th class="border border-gray-400 p-2">تاريخ الحجز</th>
                    <th class="border border-gray-400 p-2">حذف</th>
                    <th class="border border-gray-400 p-2">تعديل</th>
                </tr>
            </thead>
            <tbody id="records">
                <!-- Records will be loaded here -->
            </tbody>
        </table>
    </main>

    <script>
        const searchInput = document.getElementById('search');
        const recordsTable = document.getElementById('records');

        function searchRecords() {
            const searchQuery = searchInput.value.trim();
            if (searchQuery) {
                fetch('../backend/حجز.php?search=' + searchQuery)
                    .then(response => response.json())
                    .then(data => {
                        recordsTable.innerHTML = '';
                        data.forEach(record => {
                            const row = document.createElement('tr');
                            row.innerHTML = `
                                <td class="border border-gray-400 p-2">${record.اسم}</td>
                                <td class="border border-gray-400 p-2">${record.تاريخ_الحجز}</td>
                                <td class="border border-gray-400 p-2">
                                    <button class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" onclick="deleteRecord(${record.id})">حذف</button>
                                </td>
                                <td class="border border-gray-400 p-2">
                                    <a href="edit_حجز.php?id=${record.id}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">تعديل</a>
                                </td>
                            `;
                            recordsTable.appendChild(row);
                        });
                    });
            } else {
                loadRecords();
            }
        }

        function loadRecords() {
            fetch('../backend/حجز.php')
                .then(response => response.json())
                .then(data => {
                    recordsTable.innerHTML = '';
                    data.forEach(record => {
                        const row = document.createElement('tr');
                        row.innerHTML = `
                            <td class="border border-gray-400 p-2">${record.اسم}</td>
                            <td class="border border-gray-400 p-2">${record.تاريخ_الحجز}</td>
                            <td class="border border-gray-400 p-2">
                                <button class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" onclick="deleteRecord(${record.id})">حذف</button>
                            </td>
                            <td class="border border-gray-400 p-2">
                                <a href="edit_حجز.php?id=${record.id}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">تعديل</a>
                            </td>
                        `;
                        recordsTable.appendChild(row);
                    });
                });
        }

        function deleteRecord(id) {
            if (confirm('هل أنت متأكد من حذف هذا السجل؟')) {
                fetch('../backend/حجز.php', {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ id: id })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        loadRecords();
                    } else {
                        alert('حدث خطأ أثناء الحذف');
                    }
                });
            }
        }

        loadRecords();
    </script>
</body>
</html>

**backend/حجز.php**

<?php
// Assuming you have a database connection established
$db = new PDO('mysql:host=localhost;dbname=database_name', 'username', 'password');

if (isset($_GET['search'])) {
    $searchQuery = $_GET['search'];
    $stmt = $db->prepare('SELECT * FROM حجز WHERE اسم LIKE :search');
    $stmt->bindParam(':search', $searchQuery);
    $stmt->execute();
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    $stmt = $db->query('SELECT * FROM حجز');
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $stmt = $db->prepare('DELETE FROM حجز WHERE id = :id');
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    echo json_encode(['success' => true]);
    exit;
}

echo json_encode($data);

Note: This code assumes you have a database connection established and a table named `حجز` with columns `id`, `اسم`, and `تاريخ_الحجز`. You should adjust the code to match your actual database schema and table names.
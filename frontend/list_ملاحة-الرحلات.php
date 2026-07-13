**list_ملاحة-الرحلات.php**

<?php
// Session validation
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
    <title>ملاحة الرحلات</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Noto Kufi Arabic', sans-serif;
            background-color: #f7f7f7;
        }
        .bg-emerald-600 {
            background-color: #008E77;
        }
        .text-teal-500 {
            color: #0097A7;
        }
    </style>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto p-4">
        <header class="bg-emerald-600 p-4">
            <nav class="flex justify-between items-center">
                <a href="index.php" class="text-teal-500 hover:text-white">الرئيسية</a>
                <div class="flex items-center">
                    <p class="text-white mr-4">مرحباً, <?php echo $_SESSION['username']; ?></p>
                    <a href="logout.php" class="text-white hover:text-emerald-600">تسجيل الخروج</a>
                </div>
            </nav>
        </header>
        <main class="p-4">
            <h1 class="text-3xl text-emerald-600 mb-4">ملاحة الرحلات</h1>
            <button class="bg-emerald-600 hover:bg-teal-500 text-white font-bold py-2 px-4 rounded" onclick="location.href='create_ملاحة-الرحلات.php'">إضافة جديد</button>
            <div class="flex justify-between items-center mb-4">
                <input type="search" id="search" class="w-full p-2 pl-10 text-sm text-gray-700 bg-gray-100 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-600" placeholder="بحث...">
                <button class="bg-emerald-600 hover:bg-teal-500 text-white font-bold py-2 px-4 rounded" onclick="searchRecords()">بحث</button>
            </div>
            <table class="w-full border-collapse border border-gray-400">
                <thead>
                    <tr>
                        <th class="border border-gray-400 p-2">الاسم</th>
                        <th class="border border-gray-400 p-2">العنوان</th>
                        <th class="border border-gray-400 p-2">الإجراءات</th>
                    </tr>
                </thead>
                <tbody id="records">
                    <!-- Records will be loaded here -->
                </tbody>
            </table>
        </main>
    </div>

    <script>
        const searchInput = document.getElementById('search');
        const recordsContainer = document.getElementById('records');

        function searchRecords() {
            const searchQuery = searchInput.value.trim();
            if (searchQuery) {
                fetch('../backend/ملاحة-الرحلات.php?search=' + searchQuery)
                    .then(response => response.json())
                    .then(data => {
                        const recordsHtml = data.map(record => `
                            <tr>
                                <td>${record.اسم}</td>
                                <td>${record.عنوان}</td>
                                <td>
                                    <a href="edit_ملاحة-الرحلات.php?id=${record.id}" class="text-emerald-600 hover:text-teal-500">تعديل</a>
                                    <button class="text-red-600 hover:text-emerald-600" onclick="deleteRecord(${record.id})">حذف</button>
                                </td>
                            </tr>
                        `).join('');
                        recordsContainer.innerHTML = recordsHtml;
                    });
            } else {
                loadRecords();
            }
        }

        function loadRecords() {
            fetch('../backend/ملاحة-الرحلات.php')
                .then(response => response.json())
                .then(data => {
                    const recordsHtml = data.map(record => `
                        <tr>
                            <td>${record.اسم}</td>
                            <td>${record.عنوان}</td>
                            <td>
                                <a href="edit_ملاحة-الرحلات.php?id=${record.id}" class="text-emerald-600 hover:text-teal-500">تعديل</a>
                                <button class="text-red-600 hover:text-emerald-600" onclick="deleteRecord(${record.id})">حذف</button>
                            </td>
                        </tr>
                    `).join('');
                    recordsContainer.innerHTML = recordsHtml;
                });
        }

        function deleteRecord(id) {
            if (confirm('هل أنت متأكد من حذف هذا السجل؟')) {
                fetch('../backend/ملاحة-الرحلات.php', {
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
                        alert(data.message);
                    }
                });
            }
        }

        loadRecords();
    </script>
</body>
</html>

**backend/ملاحة-الرحلات.php**

<?php
// Database connection
$conn = new mysqli('localhost', 'username', 'password', 'database');

// Search query
if (isset($_GET['search'])) {
    $searchQuery = $_GET['search'];
    $sql = "SELECT * FROM ملاحة_الرحلات WHERE اسم LIKE '%$searchQuery%' OR عنوان LIKE '%$searchQuery%'";
} else {
    $sql = "SELECT * FROM ملاحة_الرحلات";
}

// Fetch records
$result = $conn->query($sql);
$data = array();
while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

// Output records
echo json_encode($data);

// Delete record
if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === 'DELETE') {
    $id = $_POST['id'];
    $sql = "DELETE FROM ملاحة_الرحلات WHERE id = '$id'";
    $conn->query($sql);
    echo json_encode(array('success' => true));
}

$conn->close();
?>
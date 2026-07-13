**list_ملاحة-الجوية.php**

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
    <title>ملاحة الجوية</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f7f7f7;
        }
        .header {
            background-color: #2c3e50;
            padding: 1rem;
            text-align: center;
        }
        .header a {
            color: #fff;
            text-decoration: none;
        }
        .header a:hover {
            color: #ccc;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
        }
        .table th, .table td {
            border: 1px solid #ddd;
            padding: 1rem;
            text-align: left;
        }
        .table th {
            background-color: #2c3e50;
            color: #fff;
        }
        .search-bar {
            width: 50%;
            padding: 1rem;
            border: 1px solid #ccc;
            border-radius: 0.5rem;
        }
        .search-bar input {
            width: 100%;
            padding: 1rem;
            border: none;
            border-radius: 0.5rem;
        }
        .search-bar button {
            background-color: #2c3e50;
            color: #fff;
            border: none;
            padding: 1rem;
            border-radius: 0.5rem;
            cursor: pointer;
        }
        .search-bar button:hover {
            background-color: #1a1d23;
        }
    </style>
</head>
<body>
    <div class="header">
        <a href="index.php">الرئيسية</a>
        <span class="text-lg font-bold">مركز إدارة الملاحة الجوية</span>
        <a href="profile.php"><?= $_SESSION['username']; ?></a>
        <a href="logout.php">تسجيل الخروج</a>
    </div>
    <div class="container mx-auto p-4">
        <h1 class="text-3xl font-bold mb-4">قائمة الملاحة الجوية</h1>
        <button class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2 px-4 rounded mb-4" onclick="location.href='create_ملاحة-الجوية.php'">إضافة جديد</button>
        <div class="search-bar">
            <input type="search" id="search" placeholder="بحث...">
            <button onclick="searchRecords()">بحث</button>
        </div>
        <table class="table">
            <thead>
                <tr>
                    <th>الرقم</th>
                    <th>اسم الملاحة</th>
                    <th>تاريخ الإضافة</th>
                    <th>الإجراءات</th>
                </tr>
            </thead>
            <tbody id="records">
                <!-- Records will be fetched here -->
            </tbody>
        </table>
    </div>

    <script>
        async function searchRecords() {
            const search = document.getElementById('search').value;
            const response = await fetch('../backend/ملاحة-الجوية.php?search=' + search);
            const data = await response.json();
            const records = document.getElementById('records');
            records.innerHTML = '';
            data.forEach((record) => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${record.id}</td>
                    <td>${record.name}</td>
                    <td>${record.date}</td>
                    <td>
                        <a href="edit_ملاحة-الجوية.php?id=${record.id}" class="bg-teal-500 hover:bg-teal-700 text-white font-bold py-2 px-4 rounded">تعديل</a>
                        <button class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" onclick="deleteRecord(${record.id})">حذف</button>
                    </td>
                `;
                records.appendChild(row);
            });
        }

        async function deleteRecord(id) {
            if (confirm('هل أنت متأكد من حذف هذا السجل؟')) {
                const response = await fetch('../backend/ملاحة-الجوية.php', {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ id: id })
                });
                if (response.ok) {
                    searchRecords();
                } else {
                    alert('حدث خطأ أثناء الحذف');
                }
            }
        }

        searchRecords();
    </script>
</body>
</html>


**backend/ملاحة-الجوية.php**

<?php
// Database connection
$conn = mysqli_connect('localhost', 'username', 'password', 'database');

// Search query
if (isset($_GET['search'])) {
    $search = $_GET['search'];
    $query = "SELECT * FROM ملاحة_الجوية WHERE name LIKE '%$search%'";
} else {
    $query = "SELECT * FROM ملاحة_الجوية";
}

// Fetch records
$result = mysqli_query($conn, $query);
$data = array();
while ($row = mysqli_fetch_assoc($result)) {
    $data[] = $row;
}

// Output records
header('Content-Type: application/json');
echo json_encode($data);

// Delete record
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $query = "DELETE FROM ملاحة_الجوية WHERE id = '$id'";
    mysqli_query($conn, $query);
    echo 'Record deleted successfully';
}
?>
**list_دوائر-رحلات.php**

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
    <title>دوائر_رحلات</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f7f7f7;
        }
        .header {
            background-color: #1a1d23;
            color: #fff;
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
        }
        .table th {
            background-color: #f0f0f0;
        }
        .search-bar {
            width: 50%;
            padding: 1rem;
            border: 1px solid #ccc;
            border-radius: 0.5rem;
        }
        .search-bar input[type="text"] {
            width: 100%;
            padding: 1rem;
            border: none;
            border-radius: 0.5rem;
        }
        .search-bar input[type="submit"] {
            background-color: #1a1d23;
            color: #fff;
            border: none;
            padding: 1rem 2rem;
            border-radius: 0.5rem;
            cursor: pointer;
        }
        .search-bar input[type="submit"]:hover {
            background-color: #2c3e50;
        }
    </style>
</head>
<body>
    <div class="header">
        <a href="index.php">الرئيسية</a>
        <span class="text-lg font-bold">مرحباً <?= $_SESSION['username'] ?></span>
        <a href="logout.php">تسجيل خروج</a>
    </div>
    <div class="container mx-auto p-4">
        <h1 class="text-3xl font-bold mb-4">دوائر_رحلات</h1>
        <button class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded" onclick="location.href='create_دوائر-رحلات.php'">إضافة جديد</button>
        <div class="search-bar">
            <input type="text" id="search" placeholder="بحث...">
            <input type="submit" value="بحث">
        </div>
        <table class="table">
            <thead>
                <tr>
                    <th>عنوان</th>
                    <th>وصف</th>
                    <th>تاريخ الإضافة</th>
                    <th>إجراءات</th>
                </tr>
            </thead>
            <tbody id="records">
                <?php
                // Fetch records from backend
                $url = '../backend/دوائر-رحلات.php';
                $response = file_get_contents($url);
                $records = json_decode($response, true);
                foreach ($records as $record) {
                    ?>
                    <tr>
                        <td><?= $record['title'] ?></td>
                        <td><?= $record['description'] ?></td>
                        <td><?= $record['created_at'] ?></td>
                        <td>
                            <a href="edit_دوائر-رحلات.php?id=<?= $record['id'] ?>" class="text-blue-500 hover:text-blue-700">تعديل</a>
                            <button class="text-red-500 hover:text-red-700" onclick="deleteRecord(<?= $record['id'] ?>)">حذف</button>
                        </td>
                    </tr>
                    <?php
                }
                ?>
            </tbody>
        </table>
    </div>

    <script>
        // Search bar functionality
        const searchInput = document.getElementById('search');
        searchInput.addEventListener('input', function() {
            const searchValue = this.value.toLowerCase();
            const records = document.getElementById('records').getElementsByTagName('tr');
            for (let i = 0; i < records.length; i++) {
                const record = records[i];
                const title = record.cells[0].textContent.toLowerCase();
                const description = record.cells[1].textContent.toLowerCase();
                if (title.includes(searchValue) || description.includes(searchValue)) {
                    record.style.display = 'table-row';
                } else {
                    record.style.display = 'none';
                }
            }
        });

        // Delete record functionality
        function deleteRecord(id) {
            fetch('../backend/دوائر-رحلات.php', {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ id: id })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('تم حذف السجل بنجاح');
                    location.reload();
                } else {
                    alert('حدث خطأ أثناء حذف السجل');
                }
            })
            .catch(error => console.error('Error:', error));
        }
    </script>
</body>
</html>

This code includes the following features:

1. Session validation: Redirects to login.php if the user is not authenticated.
2. Header navigation: Links to index.php, displays the current user's info, and provides a logout link.
3. Table: Displays a list of records with actions (Edit and Delete).
4. Search bar: Filters elements in real-time.
5. AJAX: Fetches list records from '../backend/دوائر-رحلات.php' (GET) and handles DELETE requests.

Note: This code assumes that the backend API is implemented and returns JSON data. You may need to modify the code to match your specific backend implementation.
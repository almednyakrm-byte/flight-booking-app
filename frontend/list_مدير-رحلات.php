**list_مدير-رحلات.php**

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
    <title>مدير رحلات</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            direction: rtl;
        }
    </style>
</head>
<body>
    <header class="bg-gray-800 text-white p-4">
        <nav class="container mx-auto flex justify-between">
            <a href="index.php" class="text-lg font-bold">الرئيسية</a>
            <div class="flex items-center">
                <span class="mr-2"><?= $_SESSION['username']; ?></span>
                <button class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" onclick="document.location='logout.php'">تسجيل الخروج</button>
            </div>
        </nav>
    </header>
    <main class="container mx-auto p-4">
        <h1 class="text-3xl font-bold mb-4">مدير رحلات</h1>
        <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" onclick="document.location='create_مدير-رحلات.php'">إضافة جديد</button>
        <div class="flex justify-between mb-4">
            <input type="search" id="search" class="w-full p-2 mr-2" placeholder="بحث...">
            <button class="bg-gray-200 hover:bg-gray-300 text-gray-600 font-bold py-2 px-4 rounded" onclick="fetchData()">بحث</button>
        </div>
        <table class="w-full border-collapse border border-gray-400">
            <thead>
                <tr>
                    <th class="border border-gray-400 p-2">المعرف</th>
                    <th class="border border-gray-400 p-2">الاسم</th>
                    <th class="border border-gray-400 p-2">الإجراءات</th>
                </tr>
            </thead>
            <tbody id="records">
                <!-- Records will be loaded here -->
            </tbody>
        </table>
    </main>
    <script>
        // Fetch data from backend
        function fetchData() {
            const search = document.getElementById('search').value;
            fetch('../backend/مدير-رحلات.php?search=' + search)
                .then(response => response.json())
                .then(data => {
                    const records = document.getElementById('records');
                    records.innerHTML = '';
                    data.forEach(record => {
                        const row = document.createElement('tr');
                        row.innerHTML = `
                            <td class="border border-gray-400 p-2">${record.id}</td>
                            <td class="border border-gray-400 p-2">${record.name}</td>
                            <td class="border border-gray-400 p-2">
                                <a href="edit_مدير-رحلات.php?id=${record.id}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mr-2">تعديل</a>
                                <button class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" onclick="deleteRecord(${record.id})">حذف</button>
                            </td>
                        `;
                        records.appendChild(row);
                    });
                });
        }

        // Delete record
        function deleteRecord(id) {
            if (confirm('هل تريد حذف هذا السجل؟')) {
                fetch('../backend/مدير-رحلات.php', {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ id: id })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        fetchData();
                    } else {
                        alert('حدث خطأ أثناء الحذف');
                    }
                });
            }
        }

        // Load initial data
        fetchData();
    </script>
</body>
</html>

This code includes a premium Tailwind UI with a header navigation, table showing list of records, and actions for editing and deleting records. It also includes a search bar that filters elements in real-time. The AJAX Javascript code fetches list records from the backend using the Fetch API and handles DELETE requests for deleting records.
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
    <title>تطبيق حجز طيران مع إدارة الرحلات والتسعير والسياسات</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        .glassmorphism-card {
            background-color: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(20px);
            border-radius: 10px;
            padding: 20px;
        }
    </style>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto p-4">
        <div class="flex justify-between items-center mb-4">
            <h1 class="text-3xl font-bold text-emerald-600">تطبيق حجز طيران مع إدارة الرحلات والتسعير والسياسات</h1>
            <button class="bg-teal-500 hover:bg-teal-700 text-white font-bold py-2 px-4 rounded" onclick="location.href='logout.php'">تسجيل خروج</button>
        </div>
        <div class="glassmorphism-card p-4 mb-4">
            <h2 class="text-2xl font-bold text-emerald-600">مرحباً بكم</h2>
            <p>إدارة رحلاتك وسياساتك مع تطبيق حجز طيران هذا.</p>
        </div>
        <div class="glassmorphism-card p-4 mb-4">
            <h2 class="text-2xl font-bold text-emerald-600">إحصائيات</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <div class="bg-white rounded-lg shadow-md p-4">
                    <h3 class="text-lg font-bold text-emerald-600">رحلات جديدة</h3>
                    <p id="new-flights-count"></p>
                </div>
                <div class="bg-white rounded-lg shadow-md p-4">
                    <h3 class="text-lg font-bold text-emerald-600">رحلات قيد التنفيذ</h3>
                    <p id="in-progress-flights-count"></p>
                </div>
                <div class="bg-white rounded-lg shadow-md p-4">
                    <h3 class="text-lg font-bold text-emerald-600">رحلات مكتملة</h3>
                    <p id="completed-flights-count"></p>
                </div>
            </div>
        </div>
        <div class="glassmorphism-card p-4 mb-4">
            <h2 class="text-2xl font-bold text-emerald-600">روابط سريعة</h2>
            <ul class="list-none">
                <li class="py-2">
                    <a href="#" class="text-emerald-600 hover:text-teal-500">إدارة رحلات</a>
                </li>
                <li class="py-2">
                    <a href="#" class="text-emerald-600 hover:text-teal-500">إدارة ملاحة</a>
                </li>
                <li class="py-2">
                    <a href="#" class="text-emerald-600 hover:text-teal-500">إدارة ملاحة الرحلات</a>
                </li>
            </ul>
        </div>
    </div>

    <script>
        // Fetch stats dynamically via Javascript API calls from the backend files
        fetch('/api/stats')
            .then(response => response.json())
            .then(data => {
                document.getElementById('new-flights-count').innerText = data.newFlightsCount;
                document.getElementById('in-progress-flights-count').innerText = data.inProgressFlightsCount;
                document.getElementById('completed-flights-count').innerText = data.completedFlightsCount;
            })
            .catch(error => console.error(error));
    </script>
</body>
</html>


This code uses Tailwind CSS for styling and includes a session check to redirect to the login page if the user is not authenticated. The dashboard layout includes a welcome message, logout button, overview stats grid, and quick links to manage modules. The stats are fetched dynamically via a Javascript API call from the backend files.

Please note that you need to replace `/api/stats` with the actual API endpoint that returns the stats data. Also, you need to create the API endpoint and the backend files to handle the API calls.

You can also add more features and functionality to the dashboard as per your requirements.
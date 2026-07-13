**create_رحلات.php**

<?php
// Start session
session_start();

// Check if user is logged in
if (!isset($_SESSION['logged_in'])) {
    header('Location: login.php');
    exit;
}

// Include header and navigation
include 'header.php';
include 'navigation.php';
?>

<div class="container mx-auto p-4 pt-6 md:p-6 lg:px-12 xl:px-24">
    <div class="flex flex-wrap -mx-4 mb-4">
        <div class="w-full px-4">
            <div class="bg-white rounded shadow-md p-4">
                <h2 class="text-lg font-bold text-emerald-600 mb-4">إضافة رحلة جديدة</h2>
                <form id="create-form" class="space-y-4">
                    <div class="flex flex-wrap -mx-4">
                        <div class="w-full md:w-1/2 xl:w-1/3 px-4 mb-4 md:mb-0">
                            <label for="title" class="block text-gray-700 text-sm font-bold mb-2">عنوان الرحلة</label>
                            <input type="text" id="title" name="title" class="block w-full px-4 py-2 text-gray-700 bg-white border border-gray-300 rounded-md focus:outline-none focus:ring focus:ring-emerald-600 focus:border-emerald-600" required>
                        </div>
                        <div class="w-full md:w-1/2 xl:w-1/3 px-4 mb-4 md:mb-0">
                            <label for="description" class="block text-gray-700 text-sm font-bold mb-2">وصف الرحلة</label>
                            <textarea id="description" name="description" class="block w-full px-4 py-2 text-gray-700 bg-white border border-gray-300 rounded-md focus:outline-none focus:ring focus:ring-emerald-600 focus:border-emerald-600" required></textarea>
                        </div>
                        <div class="w-full md:w-1/2 xl:w-1/3 px-4 mb-4 md:mb-0">
                            <label for="date" class="block text-gray-700 text-sm font-bold mb-2">تاريخ الرحلة</label>
                            <input type="date" id="date" name="date" class="block w-full px-4 py-2 text-gray-700 bg-white border border-gray-300 rounded-md focus:outline-none focus:ring focus:ring-emerald-600 focus:border-emerald-600" required>
                        </div>
                        <div class="w-full md:w-1/2 xl:w-1/3 px-4 mb-4 md:mb-0">
                            <label for="time" class="block text-gray-700 text-sm font-bold mb-2">وقت الرحلة</label>
                            <input type="time" id="time" name="time" class="block w-full px-4 py-2 text-gray-700 bg-white border border-gray-300 rounded-md focus:outline-none focus:ring focus:ring-emerald-600 focus:border-emerald-600" required>
                        </div>
                        <div class="w-full md:w-1/2 xl:w-1/3 px-4 mb-4 md:mb-0">
                            <label for="location" class="block text-gray-700 text-sm font-bold mb-2">مكان الرحلة</label>
                            <input type="text" id="location" name="location" class="block w-full px-4 py-2 text-gray-700 bg-white border border-gray-300 rounded-md focus:outline-none focus:ring focus:ring-emerald-600 focus:border-emerald-600" required>
                        </div>
                        <div class="w-full px-4 mb-4">
                            <button type="submit" class="bg-teal-500 hover:bg-teal-700 text-white font-bold py-2 px-4 rounded">إضافة رحلة</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#create-form').submit(function(e) {
            e.preventDefault();
            var formData = $(this).serialize();
            $.ajax({
                type: 'POST',
                url: '../backend/رحلات.php',
                data: formData,
                success: function(response) {
                    if (response == 'success') {
                        window.location.href = 'list_رحلات.php';
                    } else {
                        alert('Error: ' + response);
                    }
                }
            });
        });
    });
</script>

<?php
// Include footer
include 'footer.php';
?>


**Note:** Make sure to replace `../backend/رحلات.php` with the actual URL of your backend script that handles the form submission. Also, ensure that the `list_رحلات.php` page exists and is accessible.
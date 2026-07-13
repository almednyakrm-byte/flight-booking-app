<!-- login.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            background-image: linear-gradient(to bottom, #1a1d23, #2b2f3a);
            background-size: 100% 300px;
            background-position: 0% 100%;
            transition: background-position 1s linear;
        }
        
        .glassmorphic {
            background: linear-gradient(90deg, rgba(255, 255, 255, 0.1), rgba(255, 255, 255, 0.1)), linear-gradient(0deg, rgba(255, 255, 255, 0.1), rgba(255, 255, 255, 0.1));
            background-blend-mode: overlay, normal;
            border-radius: 10px;
            padding: 20px;
        }
        
        .glassmorphic::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, rgba(255, 255, 255, 0.1), rgba(255, 255, 255, 0.1));
            background-blend-mode: overlay, normal;
            border-radius: 10px;
            filter: blur(10px);
        }
        
        .glassmorphic::after {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, rgba(255, 255, 255, 0.1), rgba(255, 255, 255, 0.1));
            background-blend-mode: overlay, normal;
            border-radius: 10px;
            filter: blur(20px);
        }
    </style>
</head>
<body>
    <div class="container mx-auto p-4 pt-6 md:p-6 lg:p-12 xl:p-12 2xl:p-12">
        <div class="glassmorphic mx-auto p-4 pt-6 md:p-6 lg:p-12 xl:p-12 2xl:p-12 bg-emerald-600 rounded-lg shadow-lg">
            <h2 class="text-3xl font-bold text-white text-center mb-4">Login</h2>
            <form id="login-form" class="space-y-4">
                <div class="space-y-1">
                    <label for="username" class="block text-sm font-medium text-white">Username</label>
                    <input type="text" id="username" name="username" class="block w-full px-3 py-2 text-sm text-gray-900 placeholder:text-gray-400 border border-gray-300 rounded-lg focus:ring-emerald-600 focus:border-emerald-600" placeholder="Username" pattern="[A-Za-z\u0600-\u06FF0-9\s]+">
                </div>
                <div class="space-y-1">
                    <label for="password" class="block text-sm font-medium text-white">Password</label>
                    <input type="password" id="password" name="password" class="block w-full px-3 py-2 text-sm text-gray-900 placeholder:text-gray-400 border border-gray-300 rounded-lg focus:ring-emerald-600 focus:border-emerald-600" placeholder="Password">
                </div>
                <button type="submit" class="w-full px-4 py-2 text-sm font-medium text-white bg-teal-500 rounded-lg hover:bg-teal-700 focus:outline-none focus:ring-2 focus:ring-teal-600">Login</button>
            </form>
            <p class="text-sm text-white text-center mt-2">Don't have an account? <a href="register.php" class="text-emerald-600 hover:text-emerald-400">Register</a></p>
        </div>
    </div>

    <script>
        const form = document.getElementById('login-form');
        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            const username = document.getElementById('username').value;
            const password = document.getElementById('password').value;
            const response = await fetch('../backend/auth.php?action=login', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ username, password })
            });
            const data = await response.json();
            if (data.success) {
                window.location.href = 'dashboard.php';
            } else {
                alert(data.message);
            }
        });
    </script>
</body>
</html>


This code uses Tailwind CSS to create a premium-looking login page with a glassmorphic layout, gradients, and a form for username and password input. The form includes standard HTML input pattern validators to support Arabic and Latin characters. The AJAX JavaScript code uses the Fetch API to submit the credentials to the backend `auth.php` file and handle the response or error alerts dynamically. The code also includes a direct link to the `register.php` page.
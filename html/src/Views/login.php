<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IMS Login - YVI Skin Care Products</title>
    <link rel="stylesheet" href="public/css/output.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
        .business-name {
            font-family: 'Arial', sans-serif; /* Adjust this if you can find the exact font */
            font-size: 6rem;
            font-weight: bold;
            color: #84cc16;
            text-align: center;
        }
        .subtext {
            font-family: 'Arial', sans-serif; /* Adjust this if you can find the exact font */
            font-size: 2rem;
            color: #84cc16;
            text-align: center;
            margin-top: -1rem;
        }
        .login-container {
            background-color: #ffffff;
            padding: 2rem;
            border-radius: 1rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>

<body class="flex min-h-screen bg-gray-900">
    <!-- Left side with the business name -->
    <div class="w-8/12 h-full flex items-center justify-center bg-gray-900 text-white">
        <div class="text-center">
            <div class="business-name">YVI</div>
            <div class="subtext">SKIN CARE PRODUCTS</div>
            <div class="subtext">INVENTORY MANAGEMENT SYSTEM</div>
        </div>
    </div>

    <!-- Login form container on the right -->
    <div class="fixed right-0 top-0 bottom-0 w-4/12 h-full p-0">
        <div class="flex justify-center items-center h-screen bg-[#84cc16] opacity-90">
            <form class="login-container w-9/12 p-6 shadow-lg bg-white rounded-xl bg-opacity-95" action="/login" method="POST">
                <h1 class="text-3xl block text-center font-semibold text-gray-800 mb-4"><i class="fa-solid fa-user"></i> Login</h1>
                <hr class="mb-4">
                <div class="mt-3">
                    <label for="username" class="block text-sm font-medium text-gray-700">Username</label>
                    <input type="text" id="username" name="username" class="border border-gray-300 w-full px-3 py-2 rounded-lg text-base focus:outline-none focus:ring-2 focus:ring-[#6b2b8e] focus:border-transparent" placeholder="Enter Username..." required>
                </div>
                <div class="mt-3">
                    <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                    <input type="password" id="password" name="password" class="border border-gray-300 w-full px-3 py-2 rounded-lg text-base focus:outline-none focus:ring-2 focus:ring-[#6b2b8e] focus:border-transparent" placeholder="Enter Password..." required>
                </div>
                <div class="mt-3 flex justify-between items-center">
                    <div class="flex items-center">
                        <input type="checkbox" id="remember" name="remember" class="h-4 w-4 text-[#2c7a7b] focus:ring-[#2c7a7b] border-gray-300 rounded">
                        <label for="remember" class="ml-2 text-sm text-gray-700">Remember Me</label>
                    </div>
                </div>
                <div class="mt-5">
                    <button type="submit" class="w-full bg-[#84cc16] text-white py-2 rounded-lg hover:bg-[#6b2b8e] font-semibold">Login</button>
                </div>
                <?php if (isset($error)) : ?>
                    <div id="error-message" class="mt-4 text-red-500 text-center"><?= $error ?></div>
                <?php endif; ?>
            </form>
        </div>
    </div>
</body>

</html>

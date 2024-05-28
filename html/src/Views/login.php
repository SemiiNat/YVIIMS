<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IMS Login - YVI Skin Care Products</title>
    <link rel="stylesheet" href="public/css/output.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
</head>

<body class="flex-auto">
    <div class="fixed right-0 top-0 bottom-0 w-4/12 h-full p-0">
        <div class="flex justify-center items-center h-screen bg-lime-600 opacity-80">
            <form class="w-9/12 p-6 shadow-lg bg-white rounded-xl bg-opacity-95" action="/login" method="POST">
                <h1 class="text-3xl block text-center font-semibold"><i class="fa-solid fa-user"></i> Login</h1>
                <hr class="mt-3">
                <div class="mt-3">
                    <label for="username" class="block text-base mb-2">Username</label>
                    <input type="text" id="username" name="username" class="border w-full text-base px-2 py-1 focus:outline-none focus:ring-0 focus:border-gray-600" placeholder="Enter Username..." required>
                </div>
                <div class="mt-3">
                    <label for="password" class="block text-base mb-2">Password</label>
                    <input type="password" id="password" name="password" class="border w-full text-base px-2 py-1 focus:outline-none focus:ring-0 focus:border-gray-600" placeholder="Enter Password..." required>
                </div>
                <div class="mt-3 flex justify-between items-center">
                    <div>
                        <input type="checkbox" id="remember" name="remember">
                        <label for="remember">Remember Me</label>
                    </div>
                </div>
                <div class="mt-5">
                    <button type="submit" class="border-2 border-lime-700 bg-lime-700 text-white py-1 w-full rounded-md hover:bg-transparent hover:text-lime-700 font-semibold">Login</button>
                </div>
                <?php if (isset($error)) : ?>
                    <div id="error-message" class="mt-3 text-red-500 text-center"><?= $error ?></div>
                <?php endif; ?>
            </form>
        </div>
    </div>
</body>

</html>
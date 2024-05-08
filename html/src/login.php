<?php
session_start([
    'cookie_lifetime' => 0,
    'cookie_path' => '/',
    'cookie_domain' => $_SERVER['HTTP_HOST'],
    'cookie_secure' => true,  // Ensure you're accessing the site via HTTPS
    'cookie_httponly' => true,
    'cookie_samesite' => 'Lax'
]);

include 'config.php';  // Ensure this path is correct

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    try {
        $stmt = $conn->prepare("SELECT PasswordHash, RoleID FROM AdminUser WHERE Username = ?");
        if ($stmt === false) throw new Exception('MySQL prepare error: ' . $conn->error);

        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows == 1) {
            $stmt->bind_result($hashed_password, $role_id);
            $stmt->fetch();

            if (password_verify($password, $hashed_password)) {
                $_SESSION["loggedin"] = true;
                $_SESSION["username"] = $username;
                $_SESSION["role_id"] = $role_id;

                error_log("Login successful: " . $username);  // Debugging line
                header("Location: ../public/dashboard.php");
                exit;
            } else {
                error_log("Password verification failed for user: " . $username);  // Debugging line
                header("Location: ../public/login.html?error=invalid_credentials");
                exit;
            }
        } else {
            error_log("No user found: " . $username);  // Debugging line
            header("Location: ../public/login.html?error=invalid_credentials");
            exit;
        }
    } catch (Exception $e) {
        error_log("Error: " . $e->getMessage());  // Log error in server logs
        header("Location: ../public/login.html?error=internal_error");
        exit;
    } finally {
        if (isset($stmt)) $stmt->close();
        $conn->close();
    }
}
?>

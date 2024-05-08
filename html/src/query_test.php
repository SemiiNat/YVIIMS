<?php
include 'config.php';  // Make sure this path is correct

// HTML to start the page and the table
echo "<!DOCTYPE html><html><head><title>User Information</title></head><body>";
echo "<h1>User Information</h1>";

// Query to fetch data from AdminUser and join with UserRole
$query = "SELECT a.Username, a.PasswordHash, r.RoleName, r.Description FROM ims_yvi_AdminUser a INNER JOIN ims_yvi_UserRole r ON a.RoleID = r.RoleID";
$result = $conn->query($query);

if ($result->num_rows > 0) {
    echo "<h2>Admin Users and Roles</h2>";
    echo "<table border='1'><tr><th>Username</th><th>Password Hash</th><th>Role Name</th><th>Role Description</th></tr>";
    // Output data of each row
    while($row = $result->fetch_assoc()) {
        echo "<tr><td>" . htmlspecialchars($row['Username']) . "</td><td>" . htmlspecialchars($row['PasswordHash']) . "</td><td>" . htmlspecialchars($row['RoleName']) . "</td><td>" . htmlspecialchars($row['Description']) . "</td></tr>";
    }
    echo "</table>";
} else {
    echo "0 results";
}

// Query to fetch data from UserPermissions
$queryPermissions = "SELECT PermissionName, Description FROM ims_yvi_UserPermissions";
$resultPermissions = $conn->query($queryPermissions);

if ($resultPermissions->num_rows > 0) {
    echo "<h2>User Permissions</h2>";
    echo "<table border='1'><tr><th>Permission Name</th><th>Description</th></tr>";
    // Output data of each row
    while($row = $resultPermissions->fetch_assoc()) {
        echo "<tr><td>" . htmlspecialchars($row['PermissionName']) . "</td><td>" . htmlspecialchars($row['Description']) . "</td></tr>";
    }
    echo "</table>";
} else {
    echo "0 results";
}

echo "</body></html>";
$conn->close();
?>

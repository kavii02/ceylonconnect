<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users - CeylonConnect</title>
    <link rel="stylesheet" href="assets/css/header.css">
    <link rel="stylesheet" href="assets/css/users.css">
</head>
<body>

    <?php include('includes/header.php'); ?>

    <div class="main-layout">
        <!-- Sidebar -->
        <div class="sidebar">
            <a href="/CeylonConnect/AdminPanel_dashboard/Dashboard.php" class="side-button">Dashboard</a>
            <a href="/CeylonConnect/AdminPanel_users/Users.php" class="side-button active">Users</a>
        </div>

        <!-- Table section -->
        <div class="container">
            <h1>Manage Users</h1>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Example static data (replace with DB logic later)
                    $users = [
                        ["001", "John Doe", "johndoe@gmail.com", "User"],
                        ["002", "Jane Smith", "janesmith@gmail.com", "User"],
                        ["003", "Alice Johnson", "alicejohnson@gmail.com", "User"],
                        ["004", "Bob Brown", "bobbrown@gmail.com", "Admin"],
                        ["005", "Lily Singh", "lilysingh@gmail.com", "User"],
                        ["006", "Hannah Collins", "hannahcollins@gmail.com", "User"]
                    ];

                    foreach ($users as $user) {
                        echo "<tr>";
                        echo "<td>{$user[0]}</td>";
                        echo "<td>{$user[1]}</td>";
                        echo "<td>{$user[2]}</td>";
                        echo "<td>{$user[3]}</td>";
                        echo "<td>
                                <button class='action-btn view-btn' onclick=\"window.location.href='viewProfile.php?id={$user[0]}'\">View Profile</button>
                                <button class='action-btn remove-btn'>Remove User</button>
                              </td>";
                        echo "</tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

</body>
</html>

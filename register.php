<?php
if (isset($_POST['email'])) {
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "user";

    $con = mysqli_connect($servername, $username, $password, $dbname);

    if (!$con) {
        die("connect to this database failed due to" . mysqli_connect_error());
    }

    $email = $_POST['email'];
    $password = $_POST['password'];

    // Use prepared statement to avoid SQL injection
    $select = "SELECT * FROM `user_details` WHERE email = ? AND password = ?";
    $stmt = mysqli_prepare($con, $select);
    mysqli_stmt_bind_param($stmt, "ss", $email, $password);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) > 0) {
        echo "User already exists";
    } else {
        // Hash the password for security
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $sql = "INSERT INTO `user_details` (`email`, `password`) VALUES (?, ?)";
        $stmt = mysqli_prepare($con, $sql);
        mysqli_stmt_bind_param($stmt, "ss", $email, $hashedPassword);

        if (mysqli_stmt_execute($stmt)) {
            echo "You're Successfully Registered";
            header("location: login.php");
            exit(); // It's better to add an exit after header redirect
        } else {
            echo "Error: " . mysqli_error($con);
        }
    }

    mysqli_stmt_close($stmt);
    mysqli_close($con);
}
?>

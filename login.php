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
        // Successful login, set a session/cookie, and redirect to a dashboard page
        session_start();
        $_SESSION['email'] = $email;
        header('Location: login.php'); // Change "dashboard.php" to the desired page
        exit();
    } else {
        echo "Invalid Email or Password";
    }

    mysqli_stmt_close($stmt);
    mysqli_close($con);
}
?>

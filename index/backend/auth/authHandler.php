<?php 
include_once("./../connection/connection.php");
if (isset($_GET['action']) && $_GET['action'] == 'registration') {
    $response = array();

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $username = $_POST['username'];
        $email = $_POST['email'];
        $password = $_POST['password'];

        // Check if the email or username already exists
        $check_sql = "SELECT * FROM users WHERE email = ? OR username = ?";
        $stmt = $conn->prepare($check_sql);
        $stmt->bind_param("ss", $email, $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $response['status'] = 'error';
            $response['message'] = 'Email or username already exists';
        } else {
            // Insert the new user into the database
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $insert_sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($insert_sql);
            $stmt->bind_param("sss", $username, $email, $hashed_password);

            if ($stmt->execute()) {
                $response['status'] = 'success';
                $response['message'] = 'Registration successful';
            } else {
                $response['status'] = 'error';
                $response['message'] = 'Registration failed';
            }
        }
        
        $stmt->close();
    } else {
        $response['status'] = 'error';
        $response['message'] = 'Invalid request method';
    }

    echo json_encode($response);
}
if (isset($_GET['action']) && $_GET['action'] == 'login') {
    $response = array();

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $email = $_POST['email'];
        $password = $_POST['password'];

        $sql = "SELECT * FROM users WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            if (password_verify($password, $row['password'])) {
                session_start();
                if ($row['role'] == 1) {
                    $_SESSION['admin_id'] = $row['id'];
                    $response['status'] = 'success';
                    $response['message'] = 'Login successful';
                    $response['role'] = 'admin';
                } else {
                    $_SESSION['user_id'] = $row['id'];
                    $response['status'] = 'success';
                    $response['message'] = 'Login successful';
                    $response['role'] = 'user';
                }
                
            } else {
                $response['status'] = 'error';
                $response['message'] = 'Invalid password';
            }
        } else {
            $response['status'] = 'error';
            $response['message'] = 'No user found with this email';
        }

        $stmt->close();
    } else {
        $response['status'] = 'error';
        $response['message'] = 'Invalid request method';
    }

    echo json_encode($response);
}
?>
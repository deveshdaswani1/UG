<?php
include_once("./../connection/connection.php");
session_start();
if (isset($_GET['action']) && $_GET['action'] == 'storedUserData') {
    $userid=$_SESSION['user_id'];
    $name = $_POST['name'];
    $age = $_POST['age'];
    $height = $_POST['height'];
    $weight = $_POST['weight'];
    $dailyCalorieIntake = $_POST['dailyCalorieIntake'];
    $dietPreference = $_POST['dietPreference'];
    $goal = $_POST['goal'];
    $bmi = $_POST['bmi'];

    $sql = "INSERT INTO user_details (userId,name, age, height, weight, daily_calorie_intake, diet_preference, goal, bmi) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isiiissss",$userid, $name, $age, $height, $weight, $dailyCalorieIntake, $dietPreference, $goal, $bmi);

    if ($stmt->execute()) {
        $response['status'] = 'success';
        $response['message'] = 'Data saved successfully';
    } else {
        $response['status'] = 'error';
        $response['message'] = 'Error: ' . $stmt->error;
    }

    $stmt->close();
    $conn->close();

    echo json_encode($response);
}
if (isset($_GET['action']) && $_GET['action'] == 'checkData') {
    if (!isset($_SESSION['user_id'])) {
        $response['status'] = 'error';
        $response['message'] = 'User not logged in';
        echo json_encode($response);
        exit();
    }

    $userid = $_SESSION['user_id'];

    $sql = "SELECT `id`,`name`, `diet_preference`, `goal`, `bmi` FROM `user_details` WHERE `userId` = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $userid);

    if ($stmt->execute()) {
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
            $stmt->bind_result($id,$name, $diet_preference, $goal, $bmi);
            $stmt->fetch();
            $response['status'] = 'success';
            $response['exists'] = true;
            $response['data'] = array(
                'id' => $id,
                'name' => $name,
                'diet_preference' => $diet_preference,
                'goal' => $goal,
                'bmi' => $bmi
            );
        } else {
            $response['status'] = 'success';
            $response['exists'] = false;
        }
    } else {
        $response['status'] = 'error';
        $response['message'] = 'Error executing query';
    }

    $stmt->close();
    $conn->close();

    echo json_encode($response);
}
if (isset($_GET['action']) && $_GET['action'] == 'fetchTheDataForThecal') {
    if (!isset($_POST['diet_preference']) || !isset($_POST['goal'])) {
        $response['status'] = 'error';
        $response['message'] = 'Missing diet preference or goal';
        echo json_encode($response);
        exit();
    }

    $userId = $_SESSION['user_id'];
    $diet_preference = $_POST['diet_preference'];
    $goal = $_POST['goal'];

    $sql = "SELECT m.id, m.name, m.calories, 
                   (SELECT imageName FROM meal_recipes mr WHERE mr.mealId = m.id LIMIT 1) as imageName,
                   (SELECT recipeText FROM meal_recipes mr WHERE mr.mealId = m.id LIMIT 1) as recipeText,
                   (SELECT COUNT(*) FROM selected_meals sm WHERE sm.userId = ? AND sm.mealId = m.id AND sm.status = 1) as selected
            FROM meals m 
            WHERE m.diet_type = ? AND m.weight_type = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iss", $userId, $diet_preference, $goal);

    if ($stmt->execute()) {
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $meals = array();
            while ($row = $result->fetch_assoc()) {
                $meals[] = $row;
            }
            $response['status'] = 'success';
            $response['data'] = $meals;
        } else {
            $response['status'] = 'success';
            $response['message'] = 'No meals found for the given criteria';
        }
    } else {
        $response['status'] = 'error';
        $response['message'] = 'Error executing query';
    }

    $stmt->close();
    $conn->close();

    echo json_encode($response);
}


if ($_GET['action'] == 'saveSelectedMeal') {
    if (!isset($_POST['id']) || !isset($_POST['name']) || !isset($_POST['calories']) || !isset($_POST['status'])) {
        $response['status'] = 'error';
        $response['message'] = 'Missing required fields';
        echo json_encode($response);
        exit();
    }

    $userId = $_SESSION['user_id'];
    $mealId = $_POST['id'];
    $name = $_POST['name'];
    $calories = $_POST['calories'];
    $status = $_POST['status'];

    // Check if the meal is already selected by the user
    $checkSql = "SELECT id FROM selected_meals WHERE userId = ? AND mealId = ?";
    $checkStmt = $conn->prepare($checkSql);
    $checkStmt->bind_param("ii", $userId, $mealId);
    $checkStmt->execute();
    $checkStmt->store_result();

    if ($checkStmt->num_rows > 0) {
        // Meal already selected, update its status
        $updateSql = "UPDATE selected_meals SET status = ? WHERE userId = ? AND mealId = ?";
        $updateStmt = $conn->prepare($updateSql);
        $updateStmt->bind_param("iii", $status, $userId, $mealId);

        if ($updateStmt->execute()) {
            $response['status'] = 'success';
            $response['message'] = 'Meal status updated successfully';
        } else {
            $response['status'] = 'error';
            $response['message'] = 'Error updating meal status';
        }
        $updateStmt->close();
    } else {
        // Meal not selected yet, insert new record
        $insertSql = "INSERT INTO selected_meals (userId, mealId, name, calories, status) VALUES (?, ?, ?, ?, ?)";
        $insertStmt = $conn->prepare($insertSql);
        $insertStmt->bind_param("iisii", $userId, $mealId, $name, $calories, $status);

        if ($insertStmt->execute()) {
            $response['status'] = 'success';
            $response['message'] = 'Meal saved successfully';
        } else {
            $response['status'] = 'error';
            $response['message'] = 'Error saving meal';
        }
        $insertStmt->close();
    }

    $checkStmt->close();
    $conn->close();

    echo json_encode($response);
}
if (isset($_GET['action'])) {
    if ($_GET['action'] == 'suggestMeal') {
        if (!isset($_POST['name']) || !isset($_POST['calories']) || !isset($_POST['dietType']) || !isset($_POST['weightType']) || !isset($_POST['status'])) {
            $response['status'] = 'error';
            $response['message'] = 'Missing required fields';
            echo json_encode($response);
            exit();
        }

        $userId = $_SESSION['user_id'];
        $name = $_POST['name'];
        $calories = $_POST['calories'];
        $dietType = $_POST['dietType'];
        $weightType = $_POST['weightType'];
        $status = $_POST['status'];

        $sql = "INSERT INTO suggested_meals (userId, name, calories, diet_type, weight_type, status) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("isisss", $userId, $name, $calories, $dietType, $weightType, $status);

        if ($stmt->execute()) {
            $response['status'] = 'success';
            $response['message'] = 'Meal suggestion saved successfully';
        } else {
            $response['status'] = 'error';
            $response['message'] = 'Error saving meal suggestion';
        }

        $stmt->close();
        $conn->close();

        echo json_encode($response);
    }
}
?>
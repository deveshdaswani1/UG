<?php
session_start();
include_once("./../connection/connection.php");

header('Content-Type: application/json');

$response = array();

if (!isset($_SESSION['user_id'])) {
    $response['status'] = 'error';
    $response['message'] = 'User not logged in';
    echo json_encode($response);
    exit();
}

$action = $_GET['action'];

switch ($action) {
    case 'save_meals':
        saveMeals($conn);
        break;

    case 'set_target':
        setTarget($conn);
        break;

    case 'get_meals':
        getMeals($conn);
        break;

    case 'get_calories':
        getCalories($conn);
        break;

    case 'update_meal':
        updateMeal($conn);
        break;

    case 'delete_meal':
        deleteMeal($conn);
        break;

    default:
        $response['status'] = 'error';
        $response['message'] = 'Invalid action';
        echo json_encode($response);
        break;
}

function saveMeals($conn) {
    global $response;
    $userId = $_SESSION['user_id'];
    $meals = $_POST['mealName'];
    $calories = $_POST['mealCalories'];
    $date = date('Y-m-d');

    $conn->begin_transaction();

    try {
        // Insert new meal entries
        $insertSql = "INSERT INTO user_meals (userId, mealName, calories, date) VALUES (?, ?, ?, ?)";
        $insertStmt = $conn->prepare($insertSql);

        foreach ($meals as $index => $meal) {
            $mealCalories = $calories[$index];
            $insertStmt->bind_param("isis", $userId, $meal, $mealCalories, $date);
            $insertStmt->execute();
        }

        $conn->commit();

        $response['status'] = 'success';
        $response['message'] = 'Meals logged successfully';
    } catch (Exception $e) {
        $conn->rollback();
        $response['status'] = 'error';
        $response['message'] = 'Error saving data: ' . $e->getMessage();
    }

    $conn->close();
    echo json_encode($response);
}

function setTarget($conn) {
    global $response;
    $userId = $_SESSION['user_id'];
    $dailyTarget = $_POST['dailyTarget'];
    $date = date('Y-m-d');

    // Insert or update daily target
    $targetSql = "INSERT INTO daily_targets (userId, date, target) VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE target = ?";
    $targetStmt = $conn->prepare($targetSql);
    $targetStmt->bind_param("isii", $userId, $date, $dailyTarget, $dailyTarget);

    if ($targetStmt->execute()) {
        $response['status'] = 'success';
        $response['message'] = 'Daily target set successfully';
    } else {
        $response['status'] = 'error';
        $response['message'] = 'Error setting daily target';
    }

    $targetStmt->close();
    $conn->close();
    echo json_encode($response);
}

function getMeals($conn) {
    global $response;
    $userId = $_SESSION['user_id'];
    $date = date('Y-m-d');

    // Fetch today's meals
    $sql = "SELECT id, mealName, calories FROM user_meals WHERE userId = ? AND date = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("is", $userId, $date);

    if ($stmt->execute()) {
        $result = $stmt->get_result();
        $meals = $result->fetch_all(MYSQLI_ASSOC);

        $response['status'] = 'success';
        $response['data'] = $meals;
    } else {
        $response['status'] = 'error';
        $response['message'] = 'Error fetching meals';
    }

    $stmt->close();
    $conn->close();
    echo json_encode($response);
}

function getCalories($conn) {
    global $response;
    $userId = $_SESSION['user_id'];
    $startDate = date('Y-m-d', strtotime('-6 days'));
    $endDate = date('Y-m-d');

    // Fetch calorie data for the last 7 days
    $sql = "SELECT date, SUM(calories) as total_calories
            FROM user_meals
            WHERE userId = ? AND date BETWEEN ? AND ?
            GROUP BY date";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iss", $userId, $startDate, $endDate);

    if ($stmt->execute()) {
        $result = $stmt->get_result();
        $data = [];
        $labels = [];
        $dailyCalories = array_fill_keys(array_map(function ($n) {
            return date('Y-m-d', strtotime('-' . $n . ' days'));
        }, range(6, 0)), 0);

        while ($row = $result->fetch_assoc()) {
            $dailyCalories[$row['date']] = (int)$row['total_calories'];
        }

        foreach ($dailyCalories as $date => $calories) {
            $labels[] = date('D', strtotime($date));
            $data[] = $calories;
        }

        // Fetch today's target
        $targetSql = "SELECT target FROM daily_targets WHERE userId = ? AND date = ?";
        $targetStmt = $conn->prepare($targetSql);
        $targetStmt->bind_param("is", $userId, $endDate);
        $targetStmt->execute();
        $targetResult = $targetStmt->get_result();
        $target = $targetResult->fetch_assoc()['target'];

        $response['status'] = 'success';
        $response['data'] = ['labels' => $labels, 'data' => $data, 'target' => $target];
    } else {
        $response['status'] = 'error';
        $response['message'] = 'Error fetching data';
    }

    $stmt->close();
    $conn->close();
    echo json_encode($response);
}

function updateMeal($conn) {
    global $response;
    $userId = $_SESSION['user_id'];
    $mealId = $_POST['id'];
    $mealName = $_POST['mealName'];
    $calories = $_POST['calories'];

    // Update meal
    $sql = "UPDATE user_meals SET mealName = ?, calories = ? WHERE id = ? AND userId = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("siii", $mealName, $calories, $mealId, $userId);

    if ($stmt->execute()) {
        $response['status'] = 'success';
        $response['message'] = 'Meal updated successfully';
    } else {
        $response['status'] = 'error';
        $response['message'] = 'Error updating meal';
    }

    $stmt->close();
    $conn->close();
    echo json_encode($response);
}

function deleteMeal($conn) {
    global $response;
    $userId = $_SESSION['user_id'];
    $mealId = $_POST['id'];

    // Delete meal
    $sql = "DELETE FROM user_meals WHERE id = ? AND userId = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $mealId, $userId);

    if ($stmt->execute()) {
        $response['status'] = 'success';
        $response['message'] = 'Meal deleted successfully';
    } else {
        $response['status'] = 'error';
        $response['message'] = 'Error deleting meal';
    }

    $stmt->close();
    $conn->close();
    echo json_encode($response);
}
?>

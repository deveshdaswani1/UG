<?php
include_once("./../connection/connection.php");
if(isset($_GET['action']) && $_GET['action'] == 'addMeal') {
    $response = array();

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $mealName = $_POST['mealName'];
        $calories = $_POST['calories'];
        $dietType = $_POST['dietType'];
        $weight = $_POST['weight'];

        $sql = "INSERT INTO meals (name, calories, diet_type, weight_type) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("siss", $mealName, $calories, $dietType, $weight);

        if ($stmt->execute()) {
            $response['status'] = 'success';
            $response['message'] = 'Meal added successfully';
        } else {
            $response['status'] = 'error';
            $response['message'] = 'Failed to add meal';
        }

        $stmt->close();
    } else {
        $response['status'] = 'error';
        $response['message'] = 'Invalid request method';
    }

    echo json_encode($response);
}
if ($_GET['action'] == 'fetchMeals' && $_SERVER['REQUEST_METHOD'] == 'GET') {
    $sql = "SELECT * FROM meals";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $meals = array();
        while ($row = $result->fetch_assoc()) {
            $meals[] = $row;
        }
        $response['status'] = 'success';
        $response['data'] = $meals;
    } else {
        $response['status'] = 'error';
        $response['message'] = 'No meals found';
    }
    echo json_encode($response);
}
if ($_GET['action'] == 'updateMeal' && $_SERVER['REQUEST_METHOD'] == 'POST') {
    $mealId = $_POST['mealId'];
    $mealName = $_POST['mealName'];
    $calories = $_POST['calories'];
    $dietType = $_POST['dietType'];
    $weight = $_POST['weight'];

    $sql = "UPDATE meals SET name = ?, calories = ?, diet_type = ?, weight_type = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sissi", $mealName, $calories, $dietType, $weight, $mealId);

    if ($stmt->execute()) {
        $response['status'] = 'success';
        $response['message'] = 'Meal updated successfully';
    } else {
        $response['status'] = 'error';
        $response['message'] = 'Failed to update meal';
    }
    echo json_encode($response);
    $stmt->close();
}
if ($_GET['action'] == 'deleteMeal' && $_SERVER['REQUEST_METHOD'] == 'POST') {
    $mealId = $_POST['mealId'];

    $sql = "DELETE FROM meals WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $mealId);

    if ($stmt->execute()) {
        $response['status'] = 'success';
        $response['message'] = 'Meal deleted successfully';
    } else {
        $response['status'] = 'error';
        $response['message'] = 'Failed to delete meal';
    }
    echo json_encode($response);
    $stmt->close();
} 
if(isset($_GET['action']) && $_GET['action'] == 'addRecipe'){
    $mealId = $_POST['mealId'];
    $recipeText = $_POST['recipeText'];

    // Handle file upload
    $targetDir = "./../../uploads/";
    $imageName = uniqid() . "_" . basename($_FILES["recipeImage"]["name"]);
    $targetFile = $targetDir . $imageName;
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

    // Check if image file is a actual image or fake image
    $check = getimagesize($_FILES["recipeImage"]["tmp_name"]);
    if($check !== false) {
        $uploadOk = 1;
    } else {
        $response['status'] = 'error';
        $response['message'] = 'File is not an image.';
        echo json_encode($response);
        exit();
    }

    // Check file size
    if ($_FILES["recipeImage"]["size"] > 500000) {
        $response['status'] = 'error';
        $response['message'] = 'Sorry, your file is too large.';
        echo json_encode($response);
        exit();
    }

    // Allow certain file formats
    if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
        $response['status'] = 'error';
        $response['message'] = 'Sorry, only JPG, JPEG, PNG & GIF files are allowed.';
        echo json_encode($response);
        exit();
    }

    if (move_uploaded_file($_FILES["recipeImage"]["tmp_name"], $targetFile)) {
        // Check if recipe already exists for the meal
        $checkSql = "SELECT id FROM meal_recipes WHERE mealId = ?";
        $checkStmt = $conn->prepare($checkSql);
        $checkStmt->bind_param("i", $mealId);
        $checkStmt->execute();
        $checkStmt->store_result();

        if ($checkStmt->num_rows > 0) {
            // Update existing recipe
            $checkStmt->bind_result($recipeId);
            $checkStmt->fetch();
            $updateSql = "UPDATE meal_recipes SET imageName = ?, recipeText = ? WHERE id = ?";
            $updateStmt = $conn->prepare($updateSql);
            $updateStmt->bind_param("ssi", $imageName, $recipeText, $recipeId);

            if ($updateStmt->execute()) {
                $response['status'] = 'success';
                $response['message'] = 'Recipe updated successfully';
            } else {
                $response['status'] = 'error';
                $response['message'] = 'Error updating recipe';
            }

            $updateStmt->close();
        } else {
            // Insert new recipe
            $insertSql = "INSERT INTO meal_recipes (mealId, imageName, recipeText) VALUES (?, ?, ?)";
            $insertStmt = $conn->prepare($insertSql);
            $insertStmt->bind_param("iss", $mealId, $imageName, $recipeText);

            if ($insertStmt->execute()) {
                $response['status'] = 'success';
                $response['message'] = 'Recipe added successfully';
            } else {
                $response['status'] = 'error';
                $response['message'] = 'Error adding recipe';
            }

            $insertStmt->close();
        }

        $checkStmt->close();
    } else {
        $response['status'] = 'error';
        $response['message'] = 'Sorry, there was an error uploading your file.';
    }

    $conn->close();
    echo json_encode($response);
}
if ($_GET['action'] == 'fetchMealsSuggested' && $_SERVER['REQUEST_METHOD'] == 'GET') {
    $sql = "SELECT * FROM `suggested_meals` ";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $meals = array();
        while ($row = $result->fetch_assoc()) {
            $meals[] = $row;
        }
        $response['status'] = 'success';
        $response['data'] = $meals;
    } else {
        $response['status'] = 'error';
        $response['message'] = 'No meals found';
    }
    echo json_encode($response);
}
if(isset($_GET['action']) && $_GET['action'] == 'acceptMeal'){
    $mealId = $_POST['mealId'];
    $name = $_POST['name'];
    $calories = $_POST['calories'];
    $dietType = $_POST['dietType'];
    $weightType = $_POST['weightType'];

    // Insert the accepted meal into the meals table
    $insertSql = "INSERT INTO meals (name, calories, diet_type, weight_type) VALUES (?, ?, ?, ?)";
    $insertStmt = $conn->prepare($insertSql);
    $insertStmt->bind_param("siss", $name, $calories, $dietType, $weightType);

    if ($insertStmt->execute()) {
        // Delete the meal from the suggested_meals table
        $deleteSql = "DELETE FROM suggested_meals WHERE id = ?";
        $deleteStmt = $conn->prepare($deleteSql);
        $deleteStmt->bind_param("i", $mealId);
        $deleteStmt->execute();
        $deleteStmt->close();

        $response['status'] = 'success';
        $response['message'] = 'Meal accepted and moved to meals table successfully';
    } else {
        $response['status'] = 'error';
        $response['message'] = 'Error accepting meal';
    }

    $insertStmt->close();
    $conn->close();
    echo json_encode($response);
}
if(isset($_GET['action']) && $_GET['action'] == 'rejectMeal'){
    $mealId = $_POST['mealId'];

    // Delete the meal from the suggested_meals table
    $deleteSql = "DELETE FROM suggested_meals WHERE id = ?";
    $deleteStmt = $conn->prepare($deleteSql);
    $deleteStmt->bind_param("i", $mealId);

    if ($deleteStmt->execute()) {
        $response['status'] = 'success';
        $response['message'] = 'Meal rejected successfully';
    } else {
        $response['status'] = 'error';
        $response['message'] = 'Error rejecting meal';
    }

    $deleteStmt->close();
    $conn->close();
    echo json_encode($response);
}
?>
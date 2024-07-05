<?php 
session_start();
if(!isset($_SESSION['admin_id'])){
    header('Location:./../index.html');
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="./../assets/css/style.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
</head>

<body>
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-4">
                <!-- Side Menu -->
                <div class="card mt-3 custom_card">
                    <div class="card-body">
                        <h4 class="card-title">Menu</h4>
                        <div class="list-group">
                            <a href="./index.php" class="list-group-item list-group-item-action active">Add Meal</a>
                            <a href="./pages/mealmanagnment.php" class="list-group-item list-group-item-action">Meal Management</a>
                            <a href="./pages/suggestedmeal.php" class="list-group-item list-group-item-action">Suggested Meal</a>
                            <a href="./pages/logout.php" class="list-group-item list-group-item-action bg-danger mt-5 text-white">Log Out</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-8 mt-3">
                <div class="card custom_card">
                    <div class="card-body">
                        <!-- Content Area -->
                        <div id="contentArea">
                            <!-- Add Meal Form -->
                            <div id="addMealContent">
                                <h2>Add Meal</h2>
                                <form id="addMealForm">
                                    <div class="form-group">
                                        <label for="mealName">Meal Name</label>
                                        <input type="text" class="form-control" id="mealName" name="mealName" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="calories">Calories</label>
                                        <input type="number" class="form-control" id="calories" name="calories" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="dietType">Type</label>
                                        <select class="form-control" id="dietType" name="dietType" required>
                                            <option value="veg">Vegetarian</option>
                                            <option value="non veg">Non-Vegetarian</option>
                                            <option value="vegan">Vegan</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="weight">Weight Type</label>
                                        <select class="form-control" id="weight" name="weight" required>
                                            <option value="gain">Weight Gain</option>
                                            <option value="Weight Loss">Weight Loss</option>
                                            <option value="maintain">maintain</option>
                                        </select>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Add Meal</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="./../assets/js/admin/addmealhere.js"></script>
</body>

</html>
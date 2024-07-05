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
    <link rel="stylesheet" href="../css/styles.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css">

</head>

<body>
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-4">
                <!-- Side Menu -->
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Menu</h4>
                        <div class="list-group">
                            <a href="./../index.php" class="list-group-item list-group-item-action">Add Meal</a>
                            <a href="./mealmanagnment.php" class="list-group-item list-group-item-action active">Meal Management</a>
                            <a href="./suggestedmeal.php" class="list-group-item list-group-item-action">Suggested Meal</a>
                            <a href="./logout.php" class="list-group-item list-group-item-action bg-danger mt-5 text-white">Log Out</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-body">
                        <h2>Meal Management</h2>
                        <div class="table-responsive">
                            <table class="table" id="mealDisplay">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Meal</th>
                                        <th>Calories</th>
                                        <th>Diet Type</th>
                                        <th>Weight Type</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                   
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="updateMealModal" tabindex="-1" aria-labelledby="updateMealModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="updateMealModalLabel">Update Meal</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="updateMealForm">
                        <input type="hidden" id="updateMealId" name="mealId">
                        <div class="form-group">
                            <label for="updateMealName">Meal Name</label>
                            <input type="text" class="form-control" id="updateMealName" name="mealName" required>
                        </div>
                        <div class="form-group">
                            <label for="updateCalories">Calories</label>
                            <input type="number" class="form-control" id="updateCalories" name="calories" required>
                        </div>
                        <div class="form-group">
                            <label for="updateDietType">Diet Type</label>
                            <select class="form-control" id="updateDietType" name="dietType" required>
                                <option value="veg">Vegetarian</option>
                                <option value="non veg">Non-Vegetarian</option>
                                <option value="vegan">Vegan</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="updateWeight">Weight Type</label>
                            <select class="form-control" id="updateWeight" name="weight" required>
                                <option value="gain">Weight Gain</option>
                                <option value="Weight Loss">Weight Loss</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Update Meal</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Recipe Modal -->
<div class="modal fade" id="addRecipeModal" tabindex="-1" role="dialog" aria-labelledby="addRecipeModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addRecipeModalLabel">Add Recipe</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="addRecipeForm">
                <div class="modal-body">
                    <input type="hidden" id="recipeMealId" name="mealId">
                    <div class="form-group">
                        <label for="recipeImage">Recipe Image</label>
                        <input type="file" class="form-control-file" id="recipeImage" name="recipeImage" required>
                    </div>
                    <div class="form-group">
                        <label for="recipeText">Recipe</label>
                        <textarea class="form-control" id="recipeText" name="recipeText" rows="4" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save Recipe</button>
                </div>
            </form>
        </div>
    </div>
</div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="./../../assets/js/admin/addMeal.js"></script>
</body>

</html>
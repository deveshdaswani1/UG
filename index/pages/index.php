<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location:./../index.html");
}
?>
<!doctype html>
<html lang="en">

<head>
    <title>Home</title>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="./../assets/css/style.css">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
</head>

<body class="bg-home">
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light custom_card">
        <a class="navbar-brand" href="#">Nutrition Tracker</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item active">
                    <a class="nav-link" href="./index.php">Home <span class="sr-only">(current)</span></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="./Calorie logger.php">Calorie logger</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link btn btn-outline-danger btn-danger text-white" href="./logout.php">Log Out</a>
                </li>
            </ul>
        </div>
    </nav>

    <div class="container-fluid">
        <div class="row justify-content-center mt-5">
            <div class="col-lg-6">
                <div class="card custom_card">
                    <div class="card-body">
                        <h4 class="card-title">BMI</h4>
                        <div class="form-group">
                            <input type="text" class="form-control" id="bmi" name="bmi" readonly>
                        </div>
                        <div class="table-responsive">
                            <table class="table" id="tableDisplay">
                                <thead>
                                    <tr>
                                        <th>Image</th>
                                        <th>name</th>
                                        <th>Colories</th>
                                        <th>View Recipe</th>
                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                            <button id="openSuggestModal" class="btn btn-primary">Suggest Meal</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6" id="form_display">
                <div class="card custom_card">
                    <div class="card-body">
                        <h2>Enter Your Details</h2>
                        <form id="userDetailsForm">
                            <div class="form-group">
                                <label for="name">Name</label>
                                <input type="text" class="form-control" id="name" name="name" required>
                            </div>
                            <div class="form-group">
                                <label for="age">Age</label>
                                <input type="number" class="form-control" id="age" name="age" required>
                            </div>
                            <div class="form-group">
                                <label for="height">Height (cm)</label>
                                <input type="number" class="form-control" id="height" name="height" required>
                            </div>
                            <div class="form-group">
                                <label for="weight">Weight (kg)</label>
                                <input type="number" class="form-control" id="weight" name="weight" required>
                            </div>
                            <div class="form-group">
                                <label for="dailyCalorieIntake">Daily Calorie Intake</label>
                                <input type="number" class="form-control" id="dailyCalorieIntake" name="dailyCalorieIntake" required>
                            </div>
                            <div class="form-group">
                                <label for="dietPreference">Diet Preference</label>
                                <select class="form-control" id="dietPreference" name="dietPreference" required>
                                    <option value="veg">Vegetarian</option>
                                    <option value="non veg">Non-Vegetarian</option>
                                    <option value="vegan">Vegan</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="goal">Goal</label>
                                <select class="form-control" id="goal" name="goal" required>
                                    <option value="gain">Gain Weight</option>
                                    <option value="Weight Loss">Lose Weight</option>
                                    <option value="maintain">Maintain Weight</option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade" id="suggestMealModal" tabindex="-1" role="dialog" aria-labelledby="suggestMealModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="suggestMealModalLabel">Suggest a Meal</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="suggestMealForm">
                        <div class="form-group">
                            <label for="mealName">Meal Name</label>
                            <input type="text" class="form-control" id="mealName" name="mealName" required>
                        </div>
                        <div class="form-group">
                            <label for="mealCalories">Calories</label>
                            <input type="number" class="form-control" id="mealCalories" name="mealCalories" required>
                        </div>
                        <div class="form-group">
                            <label for="dietType">Diet Type</label>
                            <select class="form-control" id="dietType" name="dietType" required>
                                <option value="veg">Vegetarian</option>
                                <option value="non veg">Non-Vegetarian</option>
                                <option value="vegan">Vegan</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="weightType">Weight Type</label>
                            <select class="form-control" id="weightType" name="weightType" required>
                                <option value="gain">Weight Gain</option>
                                <option value="lose">Weight Loss</option>
                                <option value="maintain">Maintain Weight</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- View Recipe Modal -->
    <div class="modal fade" id="viewRecipeModal" tabindex="-1" role="dialog" aria-labelledby="recipeModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="recipeModalLabel">Recipe</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <img id="recipeImage" src="" alt="Recipe Image" class="img-fluid mb-3">
                    <p id="recipeText"></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    <script src="./../assets/js/home/home.js"></script>
</body>

</html>
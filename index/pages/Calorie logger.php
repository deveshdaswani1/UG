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

<body class="bg-other">
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light custom_card">
        <a class="navbar-brand" href="#">Nutrition Tracker</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" href="./index.php">Home <span class="sr-only">(current)</span></a>
                </li>
                <li class="nav-item active">
                    <a class="nav-link" href="./Calorie logger.php">Calorie logger</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link btn btn-outline-danger btn-danger text-white" href="./logout.php">Log Out</a>
                </li>
            </ul>
        </div>
    </nav>

    <div class="container-fluid mt-4">
        <div class="row">
            <div class="col-lg-4">
                <div class="card custom_card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <h3 class="mt-4">Today's Meals</h3>
                            <table class="table" id="mealTable">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Meal Name</th>
                                        <th>Calories</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-8">
                <div class="card custom_card">
                    <div class="card-body">
                        <h2>Calorie Logger</h2>
                        <form id="calorieForm">
                            <div id="mealInputs">
                                <div class="form-row align-items-end">
                                    <div class="col">
                                        <label for="mealName1">Meal Name</label>
                                        <input type="text" class="form-control" id="mealName1" name="mealName[]" required>
                                    </div>
                                    <div class="col">
                                        <label for="mealCalories1">Calories</label>
                                        <input type="number" class="form-control" id="mealCalories1" name="mealCalories[]" required>
                                    </div>
                                </div>
                            </div>
                            <button type="button" class="btn btn-primary mt-3" id="addMealBtn">Add More</button>
                            <button type="submit" class="btn btn-success mt-3">Submit Meals</button>
                        </form>
                        <div class="form-group mt-3">
                            <label for="dailyTarget">Daily Calorie Target</label>
                            <input type="number" class="form-control" id="dailyTarget" name="dailyTarget" required>
                            <button type="button" class="btn btn-primary mt-3" id="setTargetBtn">Set Target</button>
                        </div>
                        <div class="col-lg-6 card custom_card">
                            <div class="card-body">
                                <div class="col-lg-12">
                                    <h3 class="mt-4">Weekly Calorie Graph</h3>
                                    <canvas id="calorieChart" width="100" height="100"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="row">

        </div>
    </div>

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    <script src="./../assets/js/home/cal.js"></script>
</body>

</html>
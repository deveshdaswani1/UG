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
                            <a href="./mealmanagnment.php" class="list-group-item list-group-item-action">Meal Management</a>
                            <a href="./suggestedmeal.php" class="list-group-item list-group-item-action active">Suggested Meal</a>
                            <a href="./logout.php" class="list-group-item list-group-item-action bg-danger mt-5 text-white">Log Out</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-body">
                        <h2>Suggested Meal Management</h2>
                        <div class="table-responsive">
                            <table class="table" id="mealDisplaySuggested">
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
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="./../../assets/js/admin/suggestedaddMeal.js"></script>
</body>

</html>
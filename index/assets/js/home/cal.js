$(document).ready(function () {
    let mealCount = 1;

    // Add more meal input fields
    $('#addMealBtn').on('click', function () {
        mealCount++;
        let mealInput = `
        <div class="form-row align-items-end mt-3">
            <div class="col">
                <label for="mealName${mealCount}">Meal Name</label>
                <input type="text" class="form-control" id="mealName${mealCount}" name="mealName[]" required>
            </div>
            <div class="col">
                <label for="mealCalories${mealCount}">Calories</label>
                <input type="number" class="form-control" id="mealCalories${mealCount}" name="mealCalories[]" required>
            </div>
            <div class="col-1">
                <button type="button" class="btn btn-danger btn-sm remove-meal-btn">X</button>
            </div>
        </div>`;
        $('#mealInputs').append(mealInput);
    });

    // Remove meal input fields
    $(document).on('click', '.remove-meal-btn', function () {
        $(this).closest('.form-row').remove();
    });

    // Handle meal submission
    $('#calorieForm').on('submit', function (e) {
        e.preventDefault();

        let formData = $(this).serialize();
        $.ajax({
            url: './../backend/function/caloriesHandler.php?action=save_meals',
            method: 'POST',
            data: formData,
            dataType: 'json',
            success: function (response) {
                if (response.status === 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: 'Meals logged successfully!'
                    });
                    loadMeals();
                    loadGraph();
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: response.message
                    });
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.error(textStatus, errorThrown);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'An error occurred while saving the data. Please try again.'
                });
            }
        });
    });

    // Set daily calorie target
    $('#setTargetBtn').on('click', function () {
        let target = $('#dailyTarget').val();
        if (!target) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Please enter a daily calorie target.'
            });
            return;
        }

        $.ajax({
            url: './../backend/function/caloriesHandler.php?action=set_target',
            method: 'POST',
            data: { dailyTarget: target },
            dataType: 'json',
            success: function (response) {
                if (response.status === 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: 'Daily target set successfully!'
                    });
                    $('#dailyTarget').prop('disabled', true);
                    $('#setTargetBtn').prop('disabled', true);
                    $('#targetDisplay').text(`Today's Target: ${target} calories`).show();
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: response.message
                    });
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.error(textStatus, errorThrown);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'An error occurred while setting the target. Please try again.'
                });
            }
        });
    });

    // Load the meals for today
    function loadMeals() {
        $.ajax({
            url: './../backend/function/caloriesHandler.php?action=get_meals',
            method: 'GET',
            dataType: 'json',
            success: function (response) {
                if (response.status === 'success') {
                    let meals = response.data;
                    let tableBody = $('#mealTable tbody');
                    tableBody.empty();
                    meals.forEach(function (meal, index) {
                        let row = `<tr>
                                      <td>${index + 1}</td>
                                      <td>${meal.mealName}</td>
                                      <td>${meal.calories}</td>
                                      <td>
                                          <button class="btn btn-warning btn-sm update-meal-btn" data-id="${meal.id}">Update</button>
                                          <button class="btn btn-danger btn-sm delete-meal-btn" data-id="${meal.id}">Delete</button>
                                      </td>
                                   </tr>`;
                        tableBody.append(row);
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: response.message
                    });
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.error(textStatus, errorThrown);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'An error occurred while loading the meals. Please try again.'
                });
            }
        });
    }

    // Load the calorie graph
    function loadGraph() {
        $.ajax({
            url: './../backend/function/caloriesHandler.php?action=get_calories',
            method: 'GET',
            dataType: 'json',
            success: function (response) {
                if (response.status === 'success') {
                    let labels = response.data.labels;
                    let data = response.data.data;
                    let target = response.data.target;
                    let ctx = document.getElementById('calorieChart').getContext('2d');
                    new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: labels,
                            datasets: [{
                                label: 'Calories',
                                data: data,
                                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                                borderColor: 'rgba(75, 192, 192, 1)',
                                borderWidth: 1
                            }, {
                                label: 'Target',
                                data: Array(labels.length).fill(target),
                                type: 'line',
                                borderColor: 'rgba(255, 99, 132, 1)',
                                fill: false
                            }]
                        },
                        options: {
                            scales: {
                                y: {
                                    beginAtZero: true
                                }
                            }
                        }
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: response.message
                    });
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.error(textStatus, errorThrown);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'An error occurred while loading the data. Please try again.'
                });
            }
        });
    }

    // Update meal
    $(document).on('click', '.update-meal-btn', function () {
        let mealId = $(this).data('id');
        let mealName = prompt('Enter new meal name:');
        let mealCalories = prompt('Enter new meal calories:');

        if (!mealName || !mealCalories) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Please enter both meal name and calories.'
            });
            return;
        }

        $.ajax({
            url: './../backend/function/caloriesHandler.php?action=update_meal',
            method: 'POST',
            data: {
                id: mealId,
                mealName: mealName,
                calories: mealCalories
            },
            dataType: 'json',
            success: function (response) {
                if (response.status === 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: 'Meal updated successfully!'
                    });
                    loadMeals();
                    loadGraph();
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: response.message
                    });
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.error(textStatus, errorThrown);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'An error occurred while updating the meal. Please try again.'
                });
            }
        });
    });

    // Delete meal
    $(document).on('click', '.delete-meal-btn', function () {
        let mealId = $(this).data('id');

        $.ajax({
            url: './../backend/function/caloriesHandler.php?action=delete_meal',
            method: 'POST',
            data: { id: mealId },
            dataType: 'json',
            success: function (response) {
                if (response.status === 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: 'Meal deleted successfully!'
                    });
                    loadMeals();
                    loadGraph();
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: response.message
                    });
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.error(textStatus, errorThrown);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'An error occurred while deleting the meal. Please try again.'
                });
            }
        });
    });

    // Load meals and graph on page load
    loadMeals();
    loadGraph();
});

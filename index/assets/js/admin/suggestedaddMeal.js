$(document).ready(function() {
    // Initialize DataTable
    var mealTable = $('#mealDisplaySuggested').DataTable();

    // Function to fetch meals
    function fetchTheMeal() {
        $.ajax({
            url: './../../backend/admin/adminHandler.php?action=fetchMealsSuggested',
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                mealTable.clear();
                data.data.forEach(function(meal, index) {
                    mealTable.row.add([
                        index + 1,
                        meal.name,
                        meal.calories,
                        meal.diet_type,
                        meal.weight_type,
                        '<button class="btn btn-sm btn-success accept-meal" data-id="' + meal.id + '" data-name="' + meal.name + '" data-calories="' + meal.calories + '" data-diet="' + meal.diet_type + '" data-weight="' + meal.weight_type + '">Accept</button>' +
                        '<button class="btn btn-sm btn-warning reject-meal" data-id="' + meal.id + '">Reject</button>'
                    ]).draw();
                });

                // Add click event handlers for accept and reject buttons
                $('.accept-meal').on('click', function() {
                    var mealId = $(this).data('id');
                    var mealName = $(this).data('name');
                    var mealCalories = $(this).data('calories');
                    var mealDiet = $(this).data('diet');
                    var mealWeight = $(this).data('weight');
                    acceptMeal(mealId, mealName, mealCalories, mealDiet, mealWeight);
                });

                $('.reject-meal').on('click', function() {
                    var mealId = $(this).data('id');
                    rejectMeal(mealId);
                });
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.error(textStatus, errorThrown);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'An error occurred while fetching the meals. Please try again.'
                });
            }
        });
    }

    // Fetch meals on document ready
    fetchTheMeal();

    // Function to accept a meal
    function acceptMeal(mealId, name, calories, dietType, weightType) {
        $.ajax({
            url: './../../backend/admin/adminHandler.php?action=acceptMeal',
            type: 'POST',
            data: {
                mealId: mealId,
                name: name,
                calories: calories,
                dietType: dietType,
                weightType: weightType
            },
            dataType: 'json',
            success: function(response) {
                if (response.status == 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: response.message
                    });
                    fetchTheMeal();
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: response.message
                    });
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.error(textStatus, errorThrown);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'An error occurred while accepting the meal. Please try again.'
                });
            }
        });
    }

    // Function to reject a meal
    function rejectMeal(mealId) {
        $.ajax({
            url: './../../backend/admin/adminHandler.php?action=rejectMeal',
            type: 'POST',
            data: { mealId: mealId },
            dataType: 'json',
            success: function(response) {
                if (response.status == 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: response.message
                    });
                    fetchTheMeal();
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: response.message
                    });
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.error(textStatus, errorThrown);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'An error occurred while rejecting the meal. Please try again.'
                });
            }
        });
    }
});

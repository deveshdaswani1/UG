$(document).ready(function() {
    // Initialize DataTable
    var mealTable = $('#mealDisplay').DataTable();

    // Function to fetch meals
    function fetchTheMeal() {
        $.ajax({
            url: './../../backend/admin/adminHandler.php?action=fetchMeals',
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
                        '<button class="btn btn-sm btn-primary update-meal" data-id="' + meal.id + '">Update</button>' +
                        '<button class="btn btn-sm btn-danger delete-meal" data-id="' + meal.id + '">Delete</button>' +
                        '<button class="btn btn-sm btn-info add-recipe" data-id="' + meal.id + '">Add Recipe</button>'
                    ]).draw();
                });

                // Add click event handlers for update, delete, and add recipe buttons
                $('.update-meal').on('click', function() {
                    var mealId = $(this).data('id');
                    var meal = data.data.find(m => m.id == mealId);
                    $('#updateMealId').val(meal.id);
                    $('#updateMealName').val(meal.name);
                    $('#updateCalories').val(meal.calories);
                    $('#updateDietType').val(meal.diet_type);
                    $('#updateWeight').val(meal.weight_type);
                    $('#updateMealModal').modal('show');
                });

                $('.delete-meal').on('click', function() {
                    var mealId = $(this).data('id');
                    deleteMeal(mealId);
                });

                $('.add-recipe').on('click', function() {
                    var mealId = $(this).data('id');
                    $('#recipeMealId').val(mealId);
                    $('#addRecipeModal').modal('show');
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

    // Handle Update Meal form submission
    $('#updateMealForm').on('submit', function(e) {
        e.preventDefault();

        var mealId = $('#updateMealId').val();
        var mealName = $('#updateMealName').val();
        var calories = $('#updateCalories').val();
        var dietType = $('#updateDietType').val();
        var weight = $('#updateWeight').val();

        $.ajax({
            url: './../../backend/admin/adminHandler.php?action=updateMeal',
            type: 'POST',
            data: {
                mealId: mealId,
                mealName: mealName,
                calories: calories,
                dietType: dietType,
                weight: weight
            },
            dataType: 'json',
            success: function(response) {
                console.log(response);
                if (response.status == 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: response.message
                    });
                    $('#updateMealModal').modal('hide');
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
                    text: 'An error occurred while updating the meal. Please try again.'
                });
            }
        });
    });

    // Function to delete a meal
    function deleteMeal(mealId) {
        $.ajax({
            url: './../../backend/admin/adminHandler.php?action=deleteMeal',
            type: 'POST',
            data: { mealId: mealId },
            dataType: 'json',
            success: function(response) {
                console.log(response);
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
                    text: 'An error occurred while deleting the meal. Please try again.'
                });
            }
        });
    }

    // Handle Add Recipe form submission
    $('#addRecipeForm').on('submit', function(e) {
        e.preventDefault();

        var formData = new FormData(this);

        $.ajax({
            url: './../../backend/admin/adminHandler.php?action=addRecipe',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            dataType: 'json',
            success: function(response) {
                console.log(response);
                if (response.status == 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: response.message
                    });
                    $('#addRecipeModal').modal('hide');
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
                    text: 'An error occurred while adding the recipe. Please try again.'
                });
            }
        });
    });
});

$(document).ready(function () {
    $.ajax({
        url: './../backend/function/functionHandler.php?action=checkData',
        type: 'POST',
        dataType: 'json',
        success: function (data) {
            if (data.exists) {
                $("#bmi").val(data.data.bmi);
                $("#form_display").css('display', 'none');
                $.ajax({
                    url: './../backend/function/functionHandler.php?action=fetchTheDataForThecal',
                    method: 'POST',
                    data: {
                        diet_preference: data.data.diet_preference,
                        goal: data.data.goal
                    },
                    dataType: 'json',
                    success: function (data) {
                        populateTable(data.data);
                    }
                });

                function populateTable(meals) {
                    var tableBody = $('#tableDisplay tbody');
                    tableBody.empty();
                    meals.forEach(function (meal) {
                        var checked = meal.selected ? 'checked' : '';
                        var row = `<tr>
                                      <td><img src="./../uploads/${meal.imageName}" width="50" height="50" alt="${meal.name}"></td>
                                      <td>${meal.name}</td>
                                      <td>${meal.calories}</td>
                                      <td><button class="btn btn-info view-recipe" data-id="${meal.id}" data-name="${meal.name}" data-image="${meal.imageName}" data-text="${meal.recipeText}">View Recipe</button></td>
                                   </tr>`;
                        tableBody.append(row);
                    });

                    // Attach event listener to the dynamically created View Recipe buttons
                    $('.view-recipe').on('click', function () {
                        var mealName = $(this).data('name');
                        var imageName = $(this).data('image');
                        var recipeText = $(this).data('text');

                        $('#recipeModalLabel').text(mealName);
                        $('#recipeImage').attr('src', './../uploads/' + imageName);
                        $('#recipeText').text(recipeText);
                        $('#viewRecipeModal').modal('show');
                    });
                }
            } else {
                $('#userDetailsForm').on('submit', function (e) {
                    e.preventDefault();

                    // Calculate BMI
                    var height = parseFloat($('#height').val()) / 100; // Convert cm to meters
                    var weight = parseFloat($('#weight').val());
                    var bmi = weight / (height * height);
                    $('#bmi').val(bmi.toFixed(2));

                    // Send data to PHP file using AJAX
                    $.ajax({
                        url: './../backend/function/functionHandler.php?action=storedUserData',
                        type: 'POST',
                        data: {
                            name: $('#name').val(),
                            age: $('#age').val(),
                            height: $('#height').val(),
                            weight: $('#weight').val(),
                            dailyCalorieIntake: $('#dailyCalorieIntake').val(),
                            dietPreference: $('#dietPreference').val(),
                            goal: $('#goal').val(),
                            bmi: bmi.toFixed(2)
                        },
                        dataType: 'json',
                        success: function (response) {
                            if (response.status == 'success') {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Success',
                                    text: response.message
                                });
                                $('#userDetailsForm')[0].reset();
                                $('#bmi').val('');
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Oops...',
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
            }
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.error(textStatus, errorThrown);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'An error occurred while checking user data. Please try again.'
            });
        }
    });

    // Open modal for suggesting a meal
    $('#openSuggestModal').on('click', function () {
        $('#suggestMealModal').modal('show');
    });

    // Handle meal suggestion form submission
    $('#suggestMealForm').on('submit', function (e) {
        e.preventDefault();

        var mealName = $('#mealName').val();
        var mealCalories = $('#mealCalories').val();
        var dietType = $('#dietType').val();
        var weightType = $('#weightType').val();

        $.ajax({
            url: './../backend/function/functionHandler.php?action=suggestMeal',
            method: 'POST',
            data: {
                name: mealName,
                calories: mealCalories,
                dietType: dietType,
                weightType: weightType,
                status: 0
            },
            dataType: 'json',
            success: function (response) {
                if (response.status === 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: 'Meal suggestion saved successfully!'
                    });
                    $('#suggestMealModal').modal('hide');
                    $('#suggestMealForm')[0].reset();
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
                    text: 'An error occurred while saving the meal suggestion. Please try again.'
                });
            }
        });
    });
});

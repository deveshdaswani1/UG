$(document).ready(function(){
    // Handle Add Meal form submission
    $('#addMealForm').on('submit', function(e) {
        e.preventDefault();

        var mealName = $('#mealName').val();
        var calories = $('#calories').val();
        var dietType = $('#dietType').val();
        var weight = $('#weight').val();
        $.ajax({
            url: './../backend/admin/adminHandler.php?action=addMeal',
            type: 'POST',
            data: {
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
                    $('#addMealForm')[0].reset();
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
                    text: 'An error occurred while adding the meal. Please try again.'
                });
            }
        });
    });
})
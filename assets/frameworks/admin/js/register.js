/*
 * Form Validation
 */

/*
 * Form Validation
 */

/*
 * Form Validation
 */
 $(function () {
    'use strict'
    $.validator.addMethod('validUrl', function(value, element) {
        var url = $.validator.methods.url.bind(this);
        return url(value, element) || url('http://' + value, element);
    }, 'Please enter a valid URL');
    $('form[id="add_business"]').validate({
        rules: {
            title: {
                required: true,
            },
            first_name: {
                required: true,
            },
            last_name: {
                required: true,
            },
            email: {
                required: true,
            },
            contact_number: {
                minlength: 10
            },
            password: {
                required: true,
                minlength: 8,
                maxlength: 20,
            },
            password_confirm: {
                required: true,
                minlength: 8,
                maxlength: 20,
                equalTo: "#password"
            },
            business_name: {
                required: true
            },
            business_website: { 
                validUrl: true
            },
            pac_input: { 
                required: true
            },
            business_phone: {
                minlength: 10,
            },
            stadium_id: {
                required: true
            },
            business_type: {
                required: true
            },
            accept_terms: {
                required: true
            }
        },
        //For custom messages
        messages: {
            title: {
                required: "Title is required"
            },
            first_name: {
                required: "First name is required"
            },
            last_name: {
                required: "Last name is required"
            },
            business_name: {
                required: "Business name is required"
            },
            pac_input: {
                required: "Address field is required"
            },
            business_website: {
                url: "Please enter valid url"
            },
            email: {
                required: "Email is required"
            },
            contact_number: {
                minlength: 'Phone number must be at least 10 characters long'
            },
            business_phone: {
                minlength: 'Phone number must be at least 10 characters long'
            },
            stadium_id: {
                required: "Please select atleast one stadium"
            },
            business_type: {
                required: "Please select business type"
            },
            accept_terms: {
                required: "Please accept terms and Privacy"
            },
            password: {
                required: 'Password is required',
                minlength: 'Password must be at least 8 - 20 characters long',
                maxlength: 'Sorry You are Exceeding the Limit',
            },
            password_confirm: {
                required: 'Confirm Password is required',
                minlength: 'Confirm Password must be at least 8 characters long',
                maxlength: 'Sorry You are Exceeding the Limit',
                equalTo: 'Confirm Password field does not match the Password field'
            }
        },
        errorElement: 'div',
        errorPlacement: function (error, element) {
            var placement = $(element).data('error');
            if (placement) {
                $(placement).append(error)
            } else {
                error.insertAfter(element);
            }
        },
        submitHandler: function (form) {
            if ($('#pac-input').val() == "") {
                $('#pac_input_error').html('please select address');
                return false;
            } else if (!$("input[type='checkbox'][name='facilities[]']:checked").length){
                $('#loaderror').html('please select facilities atleast one');
            } else {
                if ($('#nav-business-tab').hasClass('active')) {
                    $('#nav-business-tab').removeClass('active').removeClass('show');
                    $('#nav_business').removeClass('active').removeClass('show');
                    $('#nav-business-tab').attr('aria-selected', false);
                    $('#nav-profile-tab').removeClass('disabled').addClass( "show active" );
                    $('#nav_profile').addClass( "show active" );
                    $('#nav-profile-tab').attr('aria-selected', true);
                } else {
                    $('#loaderror').html('');
                    $(this).valid();
                    $('#pac_input_error').html('');
                    $('#submit').attr('disabled', true);
                    form.submit();
                }
            }
        }
    });

    $('form[id="update_business"]').validate({
        rules: {
            title: {
                required: true,
            },
            first_name: {
                required: true,
            },
            last_name: {
                required: true,
            },
            contact_number: {
                minlength: 10
            },
            business_name: {
                required: true
            },
            business_website: { 
                validUrl: true
            },
            pac_input: { 
                required: true
            },
            business_phone: {
                minlength: 10,
            },
            stadium_id: {
                required: true
            },
            business_type: {
                required: true
            }
        },
        //For custom messages
        messages: {
            title: {
                required: "Title is required"
            },
            first_name: {
                required: "First name is required"
            },
            last_name: {
                required: "Last name is required"
            },
            business_name: {
                required: "Business name is required"
            },
            pac_input: {
                required: "Address field is required"
            },
            business_website: {
                url: "Please enter valid url"
            },
            contact_number: {
                minlength: 'Phone number must be at least 10 characters long'
            },
            phone: {
                minlength: 'Phone number must be at least 10 characters long'
            },
            business_phone: {
                minlength: 'Phone number must be at least 10 characters long'
            },
            stadium_id: {
                required: "Please select atleast one stadium"
            },
            business_type: {
                required: "Please select business type"
            }
        },
        errorElement: 'div',
        errorPlacement: function (error, element) {
            var placement = $(element).data('error');
            if (placement) {
                $(placement).append(error)
            } else {
                error.insertAfter(element);
            }
        },
        submitHandler: function (form) {
            if($('#pac-input').val() == ""){
                $('#pac_input_error').html('please select address');
                return false;
            }else if (!$("input[type='checkbox'][name='facilities[]']:checked").length){
                $('#loaderror').html('please select facilities atleast one');
            }else {
                $('#loaderror').html('');
                $(this).valid();
                $('#pac_input_error').html('');
                $('#submit').attr('disabled', true);
                form.submit()
            }
        }
    });
});


 function check_email_exists(e) {
    var email = $(e).val();
    $.ajax({
        type: "post",
        url: baseUrl+"admin/business/check_email_exists",
        data: {email: email},
        dataType: "json",
        success: function (data) {
            if(data.resultcode == 0) {
                $('.email-validation').html(data.message);
                // $('#email').val('');
            } else {
                $('.email-validation').html('');
            }
        },
        error: function (data) {
        }
    });
 } 


document.getElementById("title").addEventListener('keydown', function (e) {
  if (this.value.length === 0 && e.which === 32) e.preventDefault();
});

document.getElementById("first_name").addEventListener('keydown', function (e) {
  if (this.value.length === 0 && e.which === 32) e.preventDefault();
});
document.getElementById("last_name").addEventListener('keydown', function (e) {
  if (this.value.length === 0 && e.which === 32) e.preventDefault();
});
document.getElementById("business_name").addEventListener('keydown', function (e) {
  if (this.value.length === 0 && e.which === 32) e.preventDefault();
});
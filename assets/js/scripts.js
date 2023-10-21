jQuery(document).ready(function ($) {


    //if (document.cookie.indexOf('popupShown=') == -1) {
    $('#popup').fadeIn(1000); // Fade in the popup form over 1 second

    // Set the "popupShown" cookie to true, with an expiry of 1 day
    var date = new Date();
    date.setTime(date.getTime() + (24 * 60 * 60 * 1000)); // 1 day expiry
    document.cookie = 'popupShown=true;expires=' + date.toUTCString() + ';path=/';
    //}


    // this is for auto-confirm of appointment and questionnaire


    $('input[name="hourappt"]').on('change', function () {
        var start_time = $('input[name="hourappt"]:checked').val();
        var new_start_time = start_time.split(':');
        var new_end_time = new_start_time[0] + ':30:00';
        $('input[name="hourapptend"]').val(new_end_time);
        var end_time = $('input[name="hourapptend"]').val();
        console.log(start_time, end_time);
    });


    var currentTab = 0; // Current tab is set to be the first tab (0)
    showTab(currentTab); // Display the current tab

    function showTab(n) {
        // This function will display the specified tab of the form...
        var x = document.getElementsByClassName("tab");
        x[n].style.display = "block";

        if ($('.tab:nth-child(' + (n + 1) + ')').hasClass('radioTab')) {
            $('.cerbo-nav-btn.next').hide();
        } else {

            $('.cerbo-nav-btn.next').show();
            if (n == 0) {
                document.getElementById("prevBtn").style.display = "none";
            } else {
                document.getElementById("prevBtn").style.display = "inline";
            }
            if (n == (x.length - 1)) {
                $('.nav-btns').hide();
                $('.tab-step').hide();
            } else {
                document.getElementById("nextBtn").innerHTML = "Next";
                $('.tab-step').show();
            }
        }

        $('.tab-step span').text(n + 1);

    }


    $('.radioTab').on('click', function () {
        nextPrev(1);
    })


    $('.newformtab').on('click', function () {
        nextPrev(1);
    })


    $('.NoOption').on('click', function () {
        nextPrev(1);
    })


    $('.cerbo-nav-btn').on('click', function () {
        if ($(this).hasClass('prev')) {
            nextPrev(-1);
        } else {
            nextPrev(1);
        }
    })


    function nextPrev(n) {
        // This function will figure out which tab to display
        var x = document.getElementsByClassName("tab");
        // Exit the function if any field in the current tab is invalid:
        if (n == 1 && !validateForm()) return false;
        // Hide the current tab:
        x[currentTab].style.display = "none";
        // Increase or decrease the current tab by 1:
        currentTab = currentTab + n;
        // if you have reached the end of the form...
        if (currentTab >= x.length) {
            // ... the form gets submitted:
            reg_form_submit();
            confirm_form_submit();
            return false;
        }
        // Otherwise, display the correct tab:
        showTab(currentTab);
    }

    $('.submit-btn').on('click', function (e) {
        e.preventDefault();
        reg_form_submit();
        confirm_form_submit();
    })

    function first_tab_validator() {
        var bday = $("#b-year").val() + '-' + $("#b-month").val() + '-' + $("#b-day").val();
        var x = new Date(bday);
        var Cnow = new Date();
        var has_vals = $("#b-year").val() == '' || $("#b-month").val() == '' || $("#b-day").val() == '';
        var proceed = true;
        if (x instanceof Date && !isNaN(x) && !has_vals) {
            if (Cnow.getFullYear() - x.getFullYear() < 18) {
                $('.bday-error').show().text('You must be at least 18 years of age.');
                proceed = false;
            } else {
                $('.bday-error').hide()

            }
        }
        if (!$('#agree').is(':checked')) {
            proceed = false;
        }
        if (proceed) {
            $('.nav-btns').show();
        } else {
            $('.nav-btns').hide();
        }

    }

    $('.bday-select select, #agree').on('change', function () {
        first_tab_validator();
    })
    $('#agree').change(function () {
        if ($(this).is(':checked')) {

        } else {

        }
    })

    function validateForm() {
        // This function deals with validation of the form fields
        var x, y, i, valid = true;
        x = document.getElementsByClassName("tab");
        y = x[currentTab].getElementsByTagName("input");
        // A loop that checks every input field in the current tab:
        for (i = 0; i < y.length; i++) {
            // If a field is empty...
            if (y[i].value == "") {
                // add an "invalid" class to the field:
                y[i].className += " invalid";
                // and set the current valid status to false
                valid = false;
            }
        }

        return valid; // return the valid status
    }

    function reg_form_submit() {
        var form = $('#regForm')[0];
        var formData = new FormData(form);

        /*$.post(
            woocommerce_params.ajax_url,
            formData,
            function (response) {
                if (response.success) {
                    Cookies.set('patient_id', response.data.patient_id, {expires: 30}, {path: '/'})
                    Cookies.set('opal_patient_id', response.data.patient_post_id, {expires: 30}, {path: '/'})
                    window.location.href = '/checkout/';
                } else {
                    alert(response.data.message);
                    location.reload();
                }
            }
        )*/


        $.ajax({
            url : woocommerce_params.ajax_url,
            type: "POST",
            data : formData,
            processData: false,
            contentType: false,
            success:function(response){
                if (response.success) {
                    console.log(response);
                    Cookies.set('post_patient_id', response.data.post_patient_id, {expires: 30}, {path: '/'})
                    Cookies.set('opal_patient_id', response.data.opal_patient_id, {expires: 30}, {path: '/'})
                    window.location.href = '/checkout/';
                } else {
                    alert(response.data.message);
                    location.reload();
                }
            }
        });
        return false;
    }


    phoneValidator();

    function phoneValidator() {
        const isNumericInput = (event) => {
            const key = event.keyCode;
            return ((key >= 48 && key <= 57) || // Allow number line
                (key >= 96 && key <= 105) // Allow number pad
            );
        };

        const isModifierKey = (event) => {
            const key = event.keyCode;
            return (event.shiftKey === true || key === 35 || key === 36) || // Allow Shift, Home, End
                (key === 8 || key === 9 || key === 13 || key === 46) || // Allow Backspace, Tab, Enter, Delete
                (key > 36 && key < 41) || // Allow left, up, right, down
                (
                    // Allow Ctrl/Command + A,C,V,X,Z
                    (event.ctrlKey === true || event.metaKey === true) &&
                    (key === 65 || key === 67 || key === 86 || key === 88 || key === 90)
                )
        };

        const enforceFormat = (event) => {
            // Input must be of a valid number format or a modifier key, and not longer than ten digits
            if (!isNumericInput(event) && !isModifierKey(event)) {
                event.preventDefault();
            }
        };

        const formatToPhone = (event) => {
            if (isModifierKey(event)) {
                return;
            }

            const input = event.target.value.replace(/\D/g, '').substring(0, 10); // First ten digits of input only
            const areaCode = input.substring(0, 3);
            const middle = input.substring(3, 6);
            const last = input.substring(6, 10);

            if (input.length > 6) {
                event.target.value = `(${areaCode}) ${middle} - ${last}`;
            } else if (input.length > 3) {
                event.target.value = `(${areaCode}) ${middle}`;
            } else if (input.length > 0) {
                event.target.value = `(${areaCode}`;
            }
        };

        const inputElement = document.getElementById('phoneNumber');
        inputElement.addEventListener('keydown', enforceFormat);
        inputElement.addEventListener('keyup', formatToPhone);
    }


    const ifYesExplain = document.querySelector('.yesOption');
    const med_prof_q8 = document.querySelector('#ifYesExplain');

    ifYesExplain.addEventListener('change', function () {
        if (this.checked) {
            med_prof_q8.style.display = 'block';
        } else {
            med_prof_q8.style.display = 'none';
        }
    });


    const yesOptionL = document.querySelector('.yesOptionL');
    const med_prof_q9Ex = document.querySelector('#med_prof_q9Ex');

    yesOptionL.addEventListener('change', function () {
        if (this.checked) {
            med_prof_q9Ex.style.display = 'block';
        } else {
            med_prof_q9Ex.style.display = 'none';
        }
    });


    const yesOption12 = document.querySelector('.yesOption12');
    const ifYesExplain_12 = document.querySelector('#ifYesExplain_12');

    yesOption12.addEventListener('change', function () {
        if (this.checked) {
            ifYesExplain_12.style.display = 'block';
        } else {
            ifYesExplain_12.style.display = 'none';
        }
    });


    const yesOptionMed = document.querySelector('.yesOptionMed');
    const auto_immu_q8Ex = document.querySelector('#auto_immu_q8Ex');

    yesOptionMed.addEventListener('change', function () {
        if (this.checked) {
            auto_immu_q8Ex.style.display = 'block';
        } else {
            auto_immu_q8Ex.style.display = 'none';
        }
    });


    const yesOption13 = document.querySelector('.yesOption13');
    const auto_immu_q13Ex = document.querySelector('#auto_immu_q13Ex');

    yesOption13.addEventListener('change', function () {
        if (this.checked) {
            auto_immu_q13Ex.style.display = 'block';
        } else {
            auto_immu_q13Ex.style.display = 'none';
        }
    });


})

jQuery(document).ready(function () {
    "use strict";

    $(".contact-form input[type='text']").blur(function () {
    var text = $(this).val().length;
    if (text >= 1) {
        $(this).parent().removeClass("error").addClass("success").find("span").html('<i class="fa fa-check"></i>')
        $(this).parent().find(".notification").remove();
    } else {
        $(this).parent().removeClass("success").addClass("error").find("span").html('<i class="fa fa-remove"></i>')
        var notification_length = $(this).parent().find(".notification").length;
        if (notification_length == 0) {
        if ($(this).hasClass('name')) {
            $(this).parent().append("<div class='notification'>Please Enter A Valid Name</div>").slideDown();
        } else if ($(this).hasClass('subject')) {
            $(this).parent().append("<div class='notification'>Please Enter A Subject</div>").slideDown();
        } else if ($(this).hasClass('message')) {
            $(this).parent().append("<div class='notification'>Please Enter A Message</div>").slideDown();
        }
        }
    }
    });
    $(".contact-form textarea").blur(function () {
    var textarea_text = $(this).val().length;
    if (textarea_text >= 10) {
        $(this).parent().removeClass("error").addClass("success").find("span").html('<i class="fa fa-check"></i>')
        $(this).parent().find(".notification").remove();
    } else {
        $(this).parent().removeClass("success").addClass("error").find("span").html('<i class="fa fa-remove"></i>')
        var notification_length = $(this).parent().find(".notification").length;
        if (notification_length == 0) {
        $(this).parent().append("<div class='notification'>Please enter 10 characters atleast</div>").slideDown();
        }
    }
    });



    function validateEmail(sEmail) {
    var filter = /^([\w-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([\w-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$/;
    if (filter.test(sEmail)) {
        return true;
    } else {
        return false;
    }
    }

    $(".contact-form input[type='email']").blur(function () {
    var sEmail = $(this).val();
    if ($.trim(sEmail).length == 0) {
        $(this).parent().removeClass("success").addClass("error").find("span").html('<i class="fa fa-remove"></i>')
        var notification_length = $(this).parent().find(".notification").length;
        if (notification_length == 0) {
        $(this).parent().append("<div class='notification'>Please Enter An Email Address</div>").slideDown();
        }
    }
    if (validateEmail(sEmail)) {
        $(this).parent().removeClass("error").addClass("success").find("span").html('<i class="fa fa-check"></i>')
        $(this).parent().find(".notification").remove();
    } else {
        $(this).parent().removeClass("success").addClass("error").find("span").html('<i class="fa fa-remove"></i>')
        var notification_length = $(this).parent().find(".notification").length;
        if (notification_length == 0) {
        $(this).parent().append("<div class='notification'>Please Enter A Valid Email Address</div>").slideDown();
        }
    }
    });




    function validatePhone(txtPhone) {
    var a = document.getElementById(txtPhone).value;
    var filter = /^[0-9-+]+$/;
    if (filter.test(a)) {
        return true;
    } else {
        return false;
    }
    }
    $(".contact-form input.phone").blur(function () {
    if (validatePhone('txtPhone')) {
        $(this).parent().removeClass("error").addClass("success").find("span").html('<i class="fa fa-check"></i>')
        $(this).parent().find(".notification").remove();
    } else {
        $(this).parent().removeClass("success").addClass("error").find("span").html('<i class="fa fa-remove"></i>')
        var notification_length = $(this).parent().find(".notification").length;
        if (notification_length == 0) {
        $(this).parent().append("<div class='notification'>Please Enter a valid Phone Number</div>");
        }
    }
    });



}); /*=== Document.Ready Ends Here ===*/


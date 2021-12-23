$(document).ready(function(){
    checkPasswordMatch();

    $("#newPassword").keyup(checkPasswordMatch);
    $("#newPassword").focusout(checkPasswordMatch);
    $("#confirmPassword").keyup(checkPasswordMatch);
    $("#confirmPassword").focusout(checkPasswordMatch);

    $('#newPassword').passtrength({
        minChars: 8,
        passwordToggle: true,
        tooltip: true,
        textInit: "Insufficiente",
        textWeak: "Debole",
        textMedium: "Media",
        textStrong: "Buona",
        textVeryStrong: "Complessa"
    });
});

function checkPasswordMatch() {
    var password = $("#newPassword").val();
    var confirmPassword = $("#confirmPassword").val();

    if (password == "" && confirmPassword == "") {
        $("#divCheckPasswordMatch").html("");
        $("#divPasswordValidation").hide();
        validatePassword()
        $("#btnSave").attr('disabled', false);
    } else if (password == "" && password != confirmPassword) {
        $("#divCheckPasswordMatch").html('<span class="invalid">Le password non corrispondono!</span>');
        $("#divPasswordValidation").hide();
        validatePassword()
        $("#btnSave").attr('disabled', true);
    } else if (password != "" && confirmPassword == "") {
        $("#divCheckPasswordMatch").html("");
        $("#divPasswordValidation").show();
        validatePassword();
        $("#btnSave").attr('disabled', true);
    } else if (password != "" && password != confirmPassword) {
        $("#divCheckPasswordMatch").html('<span class="invalid">Le password non corrispondono!');
        $("#divPasswordValidation").show();
        validatePassword();
        $("#btnSave").attr('disabled', true);
    } else if (password != "" && password == confirmPassword) {
        $("#divCheckPasswordMatch").html('<span class="valid">Le password corrispondono.');
        $("#divPasswordValidation").show();
        if (validatePassword()) {
            $("#btnSave").attr('disabled', false);
        } else{
            $("#btnSave").attr('disabled', true);
        }

    }
}

function validatePassword() {
    var password = $("#newPassword").val();
    var valid = true;

    // Validazione lettera minuscola
    var lowerCaseLetters = /[a-z]/g;
    if (lowerCaseLetters.test(password)) {
        $('#divPasswordValidation #lowercaseValidation').removeClass("invalid");
        $('#divPasswordValidation #lowercaseValidation').addClass("valid");
    } else {
        $('#divPasswordValidation #lowercaseValidation').remove("valid");
        $('#divPasswordValidation #lowercaseValidation').addClass("invalid");
        valid = false;
    }

    // Validazione lettera maiuscola
    var upperCaseLetters = /[A-Z]/g;
    if (upperCaseLetters.test(password)) {
        $('#divPasswordValidation #uppercaseValidation').removeClass("invalid");
        $('#divPasswordValidation #uppercaseValidation').addClass("valid");
    } else {
        $('#divPasswordValidation #uppercaseValidation').removeClass("valid");
        $('#divPasswordValidation #uppercaseValidation').addClass("invalid");
        valid = false;
    }

    // Validazione numero
    var numbers = /[0-9]/g;
    if (numbers.test(password)) {
        $('#divPasswordValidation #numberValidation').removeClass("invalid");
        $('#divPasswordValidation #numberValidation').addClass("valid");
    } else {
        $('#divPasswordValidation #numberValidation').removeClass("valid");
        $('#divPasswordValidation #numberValidation').addClass("invalid");
        valid = false;
    }

    // validazione carattere speciale
    var specialChars = /[!-\/:-@[-`{-~]/;
    if (specialChars.test(password)) {
        $('#divPasswordValidation #specialValidation').removeClass("invalid");
        $('#divPasswordValidation #specialValidation').addClass("valid");
    } else {
        $('#divPasswordValidation #specialValidation').removeClass("valid");
        $('#divPasswordValidation #specialValidation').addClass("invalid");
        valid = false;
    }

    // Validazione lunghezza
    if (password.length >= 8) {
        $('#divPasswordValidation #lengthValidation').removeClass("invalid");
        $('#divPasswordValidation #lengthValidation').addClass("valid");
    } else {
        $('#divPasswordValidation #lengthValidation').removeClass("valid");
        $('#divPasswordValidation #lengthValidation').addClass("invalid");
        valid = false;
    }

    return valid;
}


// PLUGIN VERIFICA ROBUSTEZZA PASSWORD

(function($, window, document, undefined) {

    var pluginName = "passtrength",
        defaults = {
            minChars: 8,
            passwordToggle: true,
            tooltip: true,
            textInit: "Not acceptable",
            textWeak: "Weak",
            textMedium: "Medium",
            textStrong: "Strong",
            textVeryStrong: "Very Strong",
            eyeImg : "img/eye.svg"
        };

    function Plugin(element, options){
        this.element = element;
        this.$elem = $(this.element);
        this.options = $.extend({}, defaults, options);
        this._defaults = defaults;
        this._name = pluginName;
        _this      = this;
        this.init();
    }

    Plugin.prototype = {
        init: function(){
            var _this    = this,
                meter    = jQuery("<div/>", {class: "passtrengthMeter"}),
                tooltip = jQuery("<div/>", {class: "tooltip", text: this.options.textInit, style: "display: none;"});

            meter.insertAfter(this.element);
            $(this.element).appendTo(meter);

            if(this.options.tooltip){
                tooltip.appendTo(meter);
            }

            this.$elem.bind("keyup keydown", function() {
                value = $(this).val();
                _this.check(value);
            });

            if(this.options.passwordToggle){
                _this.togglePassword();
            }

        },

        check: function(value){
            var secureTotal   = 0,
                chars         = 0,
                lowers        = 0,
                capitals      = 0,
                number        = 0,
                special       = 0;
                long          = 0;
                numbers       = 0;
                specials      = 0;
                lowerCase     = new RegExp("[a-z]"),
                upperCase     = new RegExp("[A-Z]"),
                oneNumber     = new RegExp("[0-9]"),
                oneSpecial    = new RegExp("[!-\/:-@[-`{-~]");
                moreNumbers   = new RegExp("(?=(.*\\d){2})");
                moreSpecials  = new RegExp("(?=(.*[!-\/:-@[-`{-~]){2})");

            if(value.length >= this.options.minChars){
                chars = 1;
            }else{
                chars = -1;
            }
            if(value.match(lowerCase)){
                lowers = 1;
            }else{
                lowers = 0;
            }
            if(value.match(upperCase)){
                capitals = 1;
            }else{
                capitals = 0;
            }
            if(value.match(oneNumber)){
                number = 1;
            }else{
                number = 0;
            }
            if(value.match(oneSpecial)){
                special = 1;
            }else{
                special = 0;
            }
            if(value.length > 10){
                long = 1;
            }else{
                long = 0;
            }
            if(value.match(moreNumbers)){
                numbers = 1;
            }else{
                numbers = 0;
            }
            if(value.match(moreSpecials)){
                specials = 1;
            }else{
                specials = 0;
            }

            secureTotal = chars + lowers + capitals + number + special + long + numbers + specials;
            securePercentage = (secureTotal / 8) * 100;

            this.addStatus(securePercentage);

        },

        addStatus: function(percentage){
            var status = "",
                text = this.options.textInit,
                meter = $(this.element).closest(".passtrengthMeter"),
                tooltip = meter.find(".tooltip");

            meter.attr("class", "passtrengthMeter");

            if(percentage >= 25){
                meter.attr("class", "passtrengthMeter");
                status = "weak";
                text = this.options.textWeak;
            }
            if(percentage >= 50){
                meter.attr("class", "passtrengthMeter");
                status = "medium";
                text = this.options.textMedium;
            }
            if(percentage >= 75){
                meter.attr("class", "passtrengthMeter");
                status = "strong";
                text = this.options.textStrong;
            }
            if(percentage >= 100){
                meter.attr("class", "passtrengthMeter");
                status = "very-strong";
                text = this.options.textVeryStrong;
            }
            meter.addClass(status);
            if(this.options.tooltip){
                tooltip.text(text);
                var tooltipWidth = tooltip.outerWidth() / 2;
                tooltip.css("margin-left", -tooltipWidth);
                tooltip.show();
            }
        },

        togglePassword: function(){
            var buttonShow     = jQuery("<span/>", {class: "showPassword", html: '<i class="fa fa-eye"></i>'}),
                passwordInput  = this;

            buttonShow.appendTo($(this.element).closest(".passtrengthMeter"));

            $(document).on("click", ".showPassword", function() {
                buttonShow.toggleClass("active");
                if (buttonShow.hasClass('active')) {
                    $(passwordInput.element).attr('type', 'text');
                } else {
                    $(passwordInput.element).attr('type', 'password');
                }
            });
        }
    };

    $.fn[pluginName] = function(options) {
        return this.each(function() {
            if (!$.data(this, "plugin_" + pluginName)) {
                $.data(this, "plugin_" + pluginName, new Plugin(this, options));
            }
        });
    };

})(jQuery, window, document);
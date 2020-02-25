$("input[name='confpassword']").blur(function () {
    if ($("input[name='password']")[1].value && $("input[name='compassword']").val()) {
        var pwd = $("input[name='password']")[1].value;
        if (this.value == pwd) {
            $(".rex_pwd").css("display", "none");
        } else {
            $(".rex_pwd").css("display", "block");
        }
    }
})
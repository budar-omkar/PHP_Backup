$(".numericOnly").keydown(function (event) {
    return (event.ctrlKey || event.altKey
        || (47 < event.keyCode && event.keyCode < 58 && event.shiftKey == false)
        || (95 < event.keyCode && event.keyCode < 106)
        || (event.keyCode == 8) || (event.keyCode == 9)
        || (event.keyCode > 34 && event.keyCode < 40)
        || (event.keyCode == 46))
});


$(".alphabetOnly").keydown(function (event) {
    var key = event.keyCode;
    return ((key >= 65 && key <= 90) || key == 8);

});




function subscribtionStatus(data) {
    $('#subscribe')[0].reset();

    if(data.status == 'success') {
        $('#successMsg').text(data.content);
        $('#successMsg').parent().show().delay(2000).fadeOut(500);
    } else {
        $('#errorMsg').text(data.content);
        $('#errorMsg').parent().show().delay(2000).fadeOut(500);
    }
}
//TODO Grab error message and put into error div
function unsubscribe(data) {
    if(data.status == 'success') {
        $('#success').show().delay(2000).fadeOut(500);
    } else {
        $('#failed').show().delay(2000).fadeOut(500);
    }
    window.location.href = data.redirectUrl;

}

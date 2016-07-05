
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
function unsubscribe(data) {

    if(data.status == 'success') {
        $('#successMsg').text(data.content);
        $('#successMsg').parent().show().delay(2000).fadeOut(500);
    } else {
        $('#errorMsg').text(data.content);
        $('#errorMsg').parent().show().delay(2000).fadeOut(500);
    }
    window.location.href = data.redirectUrl;

}

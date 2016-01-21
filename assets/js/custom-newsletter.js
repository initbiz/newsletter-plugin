
function subscribtionStatus(data) {
    $('#subscribe')[0].reset();

    if(data.status == 'success') {
        $('#success').show().delay(2000).fadeOut(500);
    } else {
        $('#failed').show().delay(2000).fadeOut(500);
    }
}

function unsubscribe(data) {
    if(data.status == 'success') {
        $('#success').show().delay(2000).fadeOut(500);
    } else {
        $('#failed').show().delay(2000).fadeOut(500);
    }
    window.location.href = data.redirectUrl;

}

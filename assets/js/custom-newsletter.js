
function subscribtionStatus(data) {
    $('#subscribe')[0].reset();

    if(data.status == 'success') {
        $('#success').show().delay(2000).fadeOut(500);
    } else if(data.status == 'taken') {
        $('#taken').show().delay(2000).fadeOut(500);
    } else {
        $('#failed').show().delay(2000).fadeOut(500);
    }
}

function unsubscribe(data) {
    console.log(data);

    if(data.status == 'success') {
        $('#success').show().delay(2000).fadeOut(500);
    } else if(data.status == 'taken') {
        $('#taken').show().delay(2000).fadeOut(500);
    } else {
        $('#failed').show().delay(2000).fadeOut(500);
    }
}

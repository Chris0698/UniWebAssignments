function getRequest( url, callbackFunction ) {
    'use strict';
    var httpRequest;

    if (window.XMLHttpRequest) { // For firefox, chrome
        httpRequest = new XMLHttpRequest();
    }
    else if (window.ActiveXObject) { // IE
        try {
            httpRequest = new ActiveXObject("Msxml2.XMLHTTP"); //for IE
        }
        catch (e) {
            try {
                httpRequest = new ActiveXObject("Microsoft.XMLHTTP");
            }
            catch (e) {}
        }
    }

    if (!httpRequest) {
        alert('Giving up :( Cannot create an XMLHTTP instance');
        return false;
    }

    //Called when the state is changed
    httpRequest.onreadystatechange = function() {
        var completed = 4, successful = 200;
        if (httpRequest.readyState == completed) {
            if (httpRequest.status == successful) {
                callbackFunction(httpRequest.responseText);
            } else {
                alert('There was a problem with the request.');
            }
        }
    };

    httpRequest.open('get', url, true);
    httpRequest.send(null);
}





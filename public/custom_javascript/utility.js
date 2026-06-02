function _(el) {
    return document.getElementById(el);
}

function ajaxUploadSupport() {

    let result = false;

    try {

        let xhr = new XMLHttpRequest();

        if ('onprogress' in xhr) {

            // Il browser supporta i W3C Progress Events

            result = true;

        } else {

            // Il browser non supporta i W3C Progress Events

        }

    } catch (e) {

        // Il browser è addirittura Internet Explorer 6 o 7...

    }



    return result;

}

//upload file con ajax
function progressHandler(event) {
    let percent = (event.loaded / event.total) * 100;
    _("percent").setAttribute("style", "width: "+percent+"%");
    _("percent").innerText = percent+"%";
}
function completeHandler(event) {
    //location.reload();
}
function errorHandler(event) {
    _("status").innerHTML = "Caricamento Fallito";
}
function abortHandler(event) {
    _("status").innerHTML = "Caricamento Annullato";
}


function upload(formID,fileID,postURL,prog) {
    if (ajaxUploadSupport()) {
        if(prog === 1){
            _("progressbar").style.display = 'block';
        }else if(prog === 2){
            _("progressbar2").style.display = 'block';
        }
        let file_ = _(fileID).files[0];

        let formdata_ = new FormData();
        formdata_.append(fileID, file_);
        let ajax_ = new XMLHttpRequest();
        ajax_.upload.addEventListener("progress", progressHandler, false);
        //ajax_.addEventListener("load", completeHandler, false);
        ajax_.addEventListener("error", errorHandler, false);
        ajax_.addEventListener("abort", abortHandler, false);
        ajax_.open("POST", postURL);
        ajax_.send(formdata_);

    } else {
        _(formID).submit();

    }
}
//modifica campi password
function mostraPassword1() {
    var x = document.getElementById("inputPassword");
    if (x.type === "password") {
      x.type = "text";
    } else {
      x.type = "password";
    }
  }
function mostraPassword2() {
  var x = document.getElementById("inputPassword2");
  if (x.type === "password") {
    x.type = "text";
  } else {
    x.type = "password";
  }
}

function mostraPassword_old() {
  var x = document.getElementById("inputPassword_old");
  if (x.type === "password") {
    x.type = "text";
  } else {
    x.type = "password";
  }
}

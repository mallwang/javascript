// EINGABEFORMULARE PRUEFEN

// Registrierungs-Formular
function checkRegistrierung(){
  if (document.forms[0].elements[0].value == "") {
    alert("Bitte Nicknamen angeben!");
    document.forms[0].elements[0].focus();
    return false;
  }
  else if (document.forms[0].elements[1].value == "") {
    alert("Bitte geben Sie Ihr E-Mail-Adresse ein!");
    document.forms[0].elements[1].focus();
    return false;
  }
  else if (document.forms[0].elements[2].value == "") {
    alert("Bitte geben Sie Ihr Passwort ein!");
    document.forms[0].elements[2].focus();
    return false;
  }
  else if (document.forms[0].elements[3].value == "") {
    alert("Bitte geben Sie Ihr Passwort nochmals ein!");
    document.forms[0].elements[3].focus();
    return false;
  }
  else{
    return true;
  }
}

// Login-Formular
function checkLogin(){
  if (document.forms[1].elements[0].value == "") {
    alert("Bitte Namen oder E-Mail-Adresse angeben!");
    document.forms[1].elements[0].focus();
    return false;
  }
  else if (document.forms[1].elements[1].value == "") {
    alert("Bitte geben Sie Ihr Passwort ein!");
    document.forms[1].elements[1].focus();
    return false;
  }
    else{
    return true;
  }
}

// NeuesPasswort-Formular
function checkNeuesPasswort(){
  if (document.forms[2].elements[0].value == "") {
    alert("Bitte geben Sie Ihren Nickname ein!");
    document.forms[2].elements[0].focus();
    return false;
  }
  else if (document.forms[2].elements[1].value == "") {
    alert("Bitte geben Sie Ihre E-Mail-Adresse ein!");
    document.forms[2].elements[1].focus();
    return false;
  }
    else{
    return true;
  }
}

// NeuesPasswort
function checkPasswort(){
  if (document.forms[0].elements[0].value == "") {
    alert("Bitte geben Sie Ihr altes Passwort ein!");
    document.forms[0].elements[0].focus();
    return false;
  }
  else if (document.forms[0].elements[1].value == "") {
    alert("Bitte geben Sie Ihr neues Passwort ein!");
    document.forms[0].elements[1].focus();
    return false;
  }
  else if (document.forms[0].elements[2].value == "") {
    alert("Bitte wiederholen Sie Ihr neues Passwort!");
    document.forms[0].elements[2].focus();
    return false;
  }
    else{
    return true;
  }
}

// Suche
function checkSuche(){
  if (document.forms[0].elements[0].value == "") {
    alert("Bitte geben Sie einen Suchbegriff ein!");
    document.forms[0].elements[0].focus();
    return false;
  }
  else{
    return true;
  }
}

// Foto
function checkFoto(){
  if (document.forms[0].elements[0].value == "") {
    alert("Bitte waehlen Sie eine Datei aus!");
    document.forms[0].elements[0].focus();
    return false;
  }
  else{
    return true;
  }
}

// NeuesPasswort-Formular
function checkNeueEmail(){
  if (document.forms[0].elements[0].value == "") {
    alert("Bitte geben Sie Ihren neue E-Mail-Adresse ein!");
    document.forms[0].elements[0].focus();
    return false;
  }
  else if (document.forms[0].elements[1].value == "") {
    alert("Bitte wiederholen Sie Ihre E-Mail-Adresse!");
    document.forms[0].elements[1].focus();
    return false;
  }
    else{
    return true;
  }
}

function checkKommentieren(i){
  if (document.forms[i].elements[1].value == "") {
    alert("Bitte geben Sie einen Text ein!");
    document.forms[i].elements[1].focus();
    return false;
  }
  else{
    return true;
  }
}

function checkNeuerBlog(){
  if (document.forms[0].elements[1].value == "") {
    alert("Bitte geben Sie einen Text ein!");
    document.forms[0].elements[1].focus();
    return false;
  }
  else{
    return true;
  }
}



function deleteAbbonnement(id){
    var answer = confirm("Moechtest du dieses Abbonnement wirklick beenden?"); 
    if (answer == true)
    {
            window.location.href='stop_abbonieren_confirm.php?id=' + id;
    }
    else{
            nothing;
    }
}

// Eigenes Kommentar in Abonnements löschen
function deleteCommentAbbonnements(id){
    var answer = confirm("Moechtest du deinen Kommentar wirklich loeschen?"); 
    if (answer == true)
    {
            window.location.href='delete_comment_confirm_abbonnement.php?id=' + id;
    }
    else{
            nothing;
    }
}

// Eigenes Kommentar in eigenen Blog löschen
function deleteCommentBlog(id){
    var answer = confirm("Moechtest du deinen Kommentar wirklich loeschen?"); 
    if (answer == true)
    {
            window.location.href='delete_comment_confirm_blog.php?id=' + id;
    }
    else{
            nothing;
    }
}

// Eigenen Blogeintrag mit Kommentaren löschen
function deleteBlog(blog_id){
    var answer = confirm("Moechtest du deinen Blogeintrag mitsamt Kommentaren wirklich loeschen?"); 
    if (answer == true)
    {
            window.location.href='delete_blog_confirm.php?blog_id=' + blog_id;
    }
    else{
            nothing;
    }
}

// Link in neuem Fenster öffnen
function popup (url) {
 fenster = window.open(url, "Profil", "width=1000,height=600,resizable=yes");
 fenster.focus();
 return false;
}
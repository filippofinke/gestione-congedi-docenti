function isValidEmail(email) {
    var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(String(email).toLowerCase());
}

function isValidName(name) {
    return isValidAlphabetAndAccents(name, 20);
}

function isValidLastName() {
    return isValidAlphabetAndAccents(name, 20);
}

function isValidLdapUsername(username) {
    return username.length > 0 && username.length <= 20;
}

function isValidPassword(password) {
    return password.length >= 6;
}

function isValidAlphabetAndAccents(text, max, min = 1) {
    var re = "/^[A-Za-zÀ-ÖØ-öø-ÿ ]{" + min + "," + max + "}$/";
    return re.test(text);
}
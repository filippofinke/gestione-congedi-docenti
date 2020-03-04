/**
 * functions.js
 * File contenente funzioni utili per la validazione di campi.
 *
 * @author Filippo Finke
 */

/**
 * Funzione che controlla se del testo è una email valida.
 *
 * @param email Il testo da controllare.
 * @return bool True se il testo è valido altrimento false.
 */
function isValidEmail(email) {
    var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(String(email).toLowerCase());
}

/**
 * Funzione che controlla se del testo è un nome valido.
 *
 * @param name Il testo da controllare.
 * @return bool True se il testo è valido altrimento false.
 */
function isValidName(name) {
    return isValidAlphabetAndAccents(name, 20);
}

/**
 * Funzione che controlla se del testo è un cognome valido.
 *
 * @param lastName Il testo da controllare.
 * @return bool True se il testo è valido altrimento false.
 */
function isValidLastName(lastName) {
    return isValidAlphabetAndAccents(lastName, 20);
}

/**
 * Funzione che controlla se del testo è un username valido.
 *
 * @param username Il testo da controllare.
 * @return bool True se il testo è valido altrimento false.
 */
function isValidLdapUsername(username) {
    return username.length > 0 && username.length <= 20;
}

/**
 * Funzione che controlla se del testo è una password valida.
 *
 * @param password Il testo da controllare.
 * @return bool True se il testo è valido altrimento false.
 */
function isValidPassword(password) {
    return password.length >= 6;
}

/**
 * Funzione che controlla se del testo contiene solamente
 * caratteri dell'alfabeto e accenti.
 *
 * @param text Il testo da controllare.
 * @param max La lunghezza massima.
 * @param min La lunghezza minima, default = 1.
 * @return bool True se il testo è valido altrimento false.
 */
function isValidAlphabetAndAccents(text, max, min = 1) {
    var re = /^[A-Za-zÀ-ÖØ-öø-ÿ ]*$/;
    return re.test(text) && text.length >= min && text.length <= max;
}

/**
 * Funzione che controlla se del testo è una descrizione valida.
 *
 * @param text Il testo da controllare.
 * @param min La lunghezza minima, default = 0.
 * @param max La lunghezza massima, default = 255.
 * @return bool True se il testo è valido altrimento false.
 */
function isValidDescription(text, min = 0, max = 255) {
    return text.length >= min && text.length <= max;
}
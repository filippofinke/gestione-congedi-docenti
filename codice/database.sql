# Database.sql
# File di creazione per il database MySQL.
#
# @author Filippo Finke

# Elimino il database se esiste già.
DROP DATABASE IF EXISTS congedi;
# Creo il database congedi.
CREATE DATABASE congedi;
# Seleziono il database da utilizzare.
USE congedi;

# Creazione della tabella permissions che verrà utilizzata 
# per la gestione dei permessi.
CREATE TABLE permissions (
    name VARCHAR(30) PRIMARY KEY
);

# Creazione della tabella users che verrà utilizzata per
# il salvataggio degli utenti LDAP.
CREATE TABLE users (
    username VARCHAR(20) PRIMARY KEY,
    name VARCHAR(20) NOT NULL,
    last_name VARCHAR(20) NOT NULL,
    permission VARCHAR(30) DEFAULT "Docente" NOT NULL,
    last_login DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL,
    FOREIGN KEY(permission) REFERENCES permissions(name) ON UPDATE CASCADE
);

# Creazione della tabella administrators che verrà utilizzata per
# la gestione degli amministratori.
CREATE TABLE administrators (
    email VARCHAR(255) PRIMARY KEY,
    name VARCHAR(20) NOT NULL,
    last_name VARCHAR(20) NOT NULL,
    password VARCHAR(255) NOT NULL,
    last_login DATETIME DEFAULT NULL
);

# Creazione della tabella tokens che verrà utilizzata per
# la gestione del recupero password.
CREATE TABLE tokens (
    email VARCHAR(255) NOT NULL,
    token VARCHAR(64) UNIQUE NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL,
    FOREIGN KEY(email) REFERENCES administrators(email) ON UPDATE CASCADE ON DELETE CASCADE
);

# Creazione della tabella reasons che verrà utilizzata per
# immagazzinare i motivi dei congedi.
CREATE TABLE reasons(
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description VARCHAR(255) NOT NULL
);

# Creazione della tabella requests che verrà utilizzata per
# immagazzinare le richieste di congedo.
CREATE TABLE requests(
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(20) NOT NULL,
    status TINYINT DEFAULT 0,
    container TINYINT DEFAULT 0,
    week ENUM("A","B") NOT NULL,
    observations VARCHAR(255),
    auditor VARCHAR(50) default null,
    paid TINYINT(1) default 0,
    hours INT default 0,
    can_be_forwarded TINYINT(1) default 0,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL,
    FOREIGN KEY(username) REFERENCES users(username)
);

# Creazione della tabella request_reason che verrà utilizzata per
# collegare le motivazioni ai congedi.
CREATE TABLE request_reason(
    request INT NOT NULL,
    reason INT NOT NULL,
    FOREIGN KEY(request) REFERENCES requests(id) ON DELETE CASCADE,
    FOREIGN KEY(reason) REFERENCES reasons(id)
);

# Creazione della tabella substitutes che verrà utilizzata per
# salvare i supplenti.
CREATE TABLE substitutes (
    request INT NOT NULL,
    from_date DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL,
    to_date DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL,
    type ENUM("SI", "SO", "SP", "SE"),
    room VARCHAR(5),
    substitute VARCHAR(30),
    class VARCHAR(15),
    FOREIGN KEY(request) REFERENCES requests(id) ON DELETE CASCADE
);

# Inserimento dei permessi di default presenti nell'applicativo web.
INSERT INTO permissions(name) VALUES ("Docente");
INSERT INTO permissions(name) VALUES ("Segreteria");
INSERT INTO permissions(name) VALUES ("Vice direzione");
INSERT INTO permissions(name) VALUES ("Direzione");

# Inserimento di un utente di prova.
INSERT INTO users(username, name, last_name) VALUES("filippo.finke", "Filippo", "Finke");

# Inserimento amministratore di prova.
INSERT INTO administrators(email, name, last_name, password) VALUES ("filippo.finke@samtrevano.ch","Filippo","Finke","$2y$10$5TLq/1LFthARn3i0AosZV.hPJBj4Ps729q9.IbyfLsi1LxLO0cBkO"); # Password: 123456

# Inserimento motivazione di prova.
INSERT INTO reasons(name, description) VALUES ("Adozione","(16 settimane, previo giustificazione dei motivi)");
INSERT INTO reasons(name, description) VALUES ("Prova","Prova");
INSERT INTO reasons(name, description) VALUES ("Prova","Prova");
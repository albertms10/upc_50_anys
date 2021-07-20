CREATE TABLE IF NOT EXISTS campus
(
    id_campus INT(4)       NOT NULL AUTO_INCREMENT,
    nom       VARCHAR(255) NOT NULL,

    PRIMARY KEY (id_campus)
);

CREATE TABLE IF NOT EXISTS ambits
(
    id_ambit INT(4)       NOT NULL AUTO_INCREMENT,
    nom      VARCHAR(255) NOT NULL,

    PRIMARY KEY (id_ambit)
);

CREATE TABLE IF NOT EXISTS esdeveniment
(
    id_esdeveniment INT(4)  NOT NULL AUTO_INCREMENT,
    titular         VARCHAR(255),
    descripcio      TEXT    NOT NULL,
    mes_info        TEXT,
    destacat        BOOLEAN NOT NULL DEFAULT '0',
    link_img        TEXT,
    id_ambit        INT(4),
    id_campus       INT(4),

    PRIMARY KEY (id_esdeveniment),
    FOREIGN KEY (id_ambit) REFERENCES ambits (id_ambit),
    FOREIGN KEY (id_campus) REFERENCES campus (id_campus)
);

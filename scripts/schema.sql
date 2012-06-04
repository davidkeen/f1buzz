CREATE TABLE IF NOT EXISTS driver (
    driver_id int(11) NOT NULL AUTO_INCREMENT,
    name varchar(255) NOT NULL,
    team varchar(255),
    active tinyint NOT NULL DEFAULT 1,
    helmet_image varchar(255),
    PRIMARY KEY (driver_id)
);

CREATE TABLE IF NOT EXISTS prediction (
    prediction_id int(11) NOT NULL AUTO_INCREMENT,
    user_id int(11) NOT NULL,
    race_id int(11) NOT NULL,
    pole int(11) NOT NULL,
    first int(11) NOT NULL,
    second int(11) NOT NULL,
    third int(11) NOT NULL,
    fastest int(11) NOT NULL,
    updated timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (prediction_id),
    UNIQUE user_id_race_id (user_id, race_id)
);

CREATE TABLE IF NOT EXISTS race (
    race_id int(11) NOT NULL AUTO_INCREMENT,
    location_id int (11) NOT NULL,
    qualifying_start datetime NOT NULL,
    race_start datetime NOT NULL,
    PRIMARY KEY (race_id)
);

CREATE TABLE IF NOT EXISTS location (
    location_id int(11) NOT NULL AUTO_INCREMENT,
    name varchar(255) NOT NULL,
    short_name CHAR(3) NOT NULL,
    PRIMARY KEY (location_id)
);

CREATE TABLE IF NOT EXISTS result (
    result_id int(11) NOT NULL AUTO_INCREMENT,
    race_id int(11) NOT NULL,
    pole int(11),
    first int(11),
    second int(11),
    third int(11),
    fastest int(11),
    PRIMARY KEY (result_id)
);

CREATE TABLE IF NOT EXISTS user (
    user_id int(11) NOT NULL AUTO_INCREMENT,
    facebook_id bigint unsigned NOT NULL,
    PRIMARY KEY (user_id),
    UNIQUE user_facebook_id_idx (facebook_id)
);
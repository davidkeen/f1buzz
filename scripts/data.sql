-- Driver table
INSERT INTO driver (name) VALUES ('Jenson Button');
INSERT INTO driver (name) VALUES ('Lewis Hamilton');
INSERT INTO driver (name) VALUES ('Michael Schumacher');
INSERT INTO driver (name) VALUES ('Nico Rosberg');
INSERT INTO driver (name) VALUES ('Sebastian Vettel');
INSERT INTO driver (name) VALUES ('Mark Webber');
INSERT INTO driver (name) VALUES ('Felipe Massa');
INSERT INTO driver (name) VALUES ('Fernando Alonso');
INSERT INTO driver (name) VALUES ('Rubens Barrichello');
INSERT INTO driver (name) VALUES ('Nico Hulkenberg');
INSERT INTO driver (name) VALUES ('Robert Kubica');
INSERT INTO driver (name) VALUES ('Vitaly Petrov');
INSERT INTO driver (name) VALUES ('Adrian Sutil');
INSERT INTO driver (name) VALUES ('Vitantonio Liuzzi');
INSERT INTO driver (name) VALUES ('Sebastien Buemi');
INSERT INTO driver (name) VALUES ('Jaime Alguersuari');
INSERT INTO driver (name) VALUES ('Jarno Trulli');
INSERT INTO driver (name) VALUES ('Heikki Kovalainen');
INSERT INTO driver (name) VALUES ('Karun Chandhok');
INSERT INTO driver (name) VALUES ('Bruno Senna');
INSERT INTO driver (name) VALUES ('Pedro de la Rosa');
INSERT INTO driver (name) VALUES ('Kamui Kobayashi');
INSERT INTO driver (name) VALUES ('Timo Glock');
INSERT INTO driver (name) VALUES ('Lucas di Grassi');

-- Location table
INSERT INTO location (location_id, name, short_name) VALUES (1, 'Bahrain', 'BHR');
INSERT INTO location (location_id, name, short_name) VALUES (2, 'Australia', 'AUS');
INSERT INTO location (location_id, name, short_name) VALUES (3, 'Malaysia', 'MYS');
INSERT INTO location (location_id, name, short_name) VALUES (4, 'China', 'CHN');
INSERT INTO location (location_id, name, short_name) VALUES (5, 'Spain', 'ESP');
INSERT INTO location (location_id, name, short_name) VALUES (6, 'Monaco', 'MCO');
INSERT INTO location (location_id, name, short_name) VALUES (7, 'Turkey', 'TUR');
INSERT INTO location (location_id, name, short_name) VALUES (8, 'Canada', 'CAN');
INSERT INTO location (location_id, name, short_name) VALUES (9, 'Europe', 'EUR');
INSERT INTO location (location_id, name, short_name) VALUES (10, 'Britain', 'GBR');
INSERT INTO location (location_id, name, short_name) VALUES (11, 'Germany', 'DEU');
INSERT INTO location (location_id, name, short_name) VALUES (12, 'Hungary', 'HUN');
INSERT INTO location (location_id, name, short_name) VALUES (13, 'Belgium', 'BEL');
INSERT INTO location (location_id, name, short_name) VALUES (14, 'Italy', 'ITA');
INSERT INTO location (location_id, name, short_name) VALUES (15, 'Singapore', 'SGP');
INSERT INTO location (location_id, name, short_name) VALUES (16, 'Japan', 'JPN');
INSERT INTO location (location_id, name, short_name) VALUES (17, 'Korea', 'KOR');
INSERT INTO location (location_id, name, short_name) VALUES (18, 'Brasil', 'BRA');
INSERT INTO location (location_id, name, short_name) VALUES (19, 'Abu Dhabi', 'ARE');

-- Race table (times in UTC)
INSERT INTO race (location_id, qualifying_start, race_start) VALUES (1, '2010-03-13 11:00:00', '2010-03-14 12:00:00');
INSERT INTO race (location_id, qualifying_start, race_start) VALUES (2, '2010-03-27 06:00:00', '2010-03-28 07:00:00');
INSERT INTO race (location_id, qualifying_start, race_start) VALUES (3, '2010-04-03 09:00:00', '2010-04-04 09:00:00');
INSERT INTO race (location_id, qualifying_start, race_start) VALUES (4, '2010-04-17 06:00:00', '2010-04-18 07:00:00');
INSERT INTO race (location_id, qualifying_start, race_start) VALUES (5, '2010-05-08 12:00:00', '2010-05-09 12:00:00');
INSERT INTO race (location_id, qualifying_start, race_start) VALUES (6, '2010-05-15 12:00:00', '2010-05-16 12:00:00');
INSERT INTO race (location_id, qualifying_start, race_start) VALUES (7, '2010-05-29 11:00:00', '2010-05-30 12:00:00');
INSERT INTO race (location_id, qualifying_start, race_start) VALUES (8, '2010-06-12 17:00:00', '2010-06-13 16:00:00');
INSERT INTO race (location_id, qualifying_start, race_start) VALUES (9, '2010-06-26 12:00:00', '2010-06-27 12:00:00');
INSERT INTO race (location_id, qualifying_start, race_start) VALUES (10, '2010-07-10 14:00:00', '2010-07-11 14:00:00');
INSERT INTO race (location_id, qualifying_start, race_start) VALUES (11, '2010-07-24 12:00:00', '2010-07-25 12:00:00');
INSERT INTO race (location_id, qualifying_start, race_start) VALUES (12, '2010-07-31 12:00:00', '2010-08-01 12:00:00');
INSERT INTO race (location_id, qualifying_start, race_start) VALUES (13, '2010-08-28 12:00:00', '2010-08-29 12:00:00');
INSERT INTO race (location_id, qualifying_start, race_start) VALUES (14, '2010-09-11 12:00:00', '2010-09-12 12:00:00');
INSERT INTO race (location_id, qualifying_start, race_start) VALUES (15, '2010-09-25 14:00:00', '2010-09-26 12:00:00');
INSERT INTO race (location_id, qualifying_start, race_start) VALUES (16, '2010-10-09 05:00:00', '2010-10-10 06:00:00');
INSERT INTO race (location_id, qualifying_start, race_start) VALUES (17, '2010-10-23 05:00:00', '2010-10-24 06:00:00');
INSERT INTO race (location_id, qualifying_start, race_start) VALUES (18, '2010-11-06 16:00:00', '2010-11-07 16:00:00');
INSERT INTO race (location_id, qualifying_start, race_start) VALUES (19, '2010-11-13 13:00:00', '2010-11-14 13:00:00');

-- User table
INSERT INTO user (user_id, facebook_id) VALUES (1, 603899601);
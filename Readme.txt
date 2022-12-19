To change the amount of players and bots change the values at lines 27 and 28 in the server.php script

installation:

you need an apache and mysql server

apache:
just put the files in /var/www/html

mysql:
CREATE DATABASE stigepill;

CREATE TABLE test (
    id int,
    currentPlayers varchar(50),
    currentPlayer int,
);

drop database if exists Kopfrechnen;

CREATE database Kopfrechnen
	CHARACTER SET utf8
	DEFAULT CHARACTER SET utf8
	COLLATE utf8_general_ci
	DEFAULT COLLATE utf8_general_ci;

use Kopfrechnen;	
	
SET NAMES utf8;
SET storage_engine=InnoDB;
SET lc_time_names = 'de_DE';

#Account des Benutzers
create table account (
	id int auto_increment,
	username varchar(30) unique NOT NULL,
	password char(32) NOT NULL,
	email varchar(50) unique,
	rolle enum('admin','lehrer','schueler'),
	vorname varchar(30),
	nachname varchar(30),
	primary key(id)
);

#Klasse
create table klasse (
	id int auto_increment,
	bezeichnung varchar(20) NOT NULL,
	primary key(id)
);

#Account - Klasse
create table accountklasse (
	account int,
	klasse int,
	primary key (account, klasse),
	constraint key2 foreign key accountklasse(account) references account(id) on delete cascade on update cascade,
	constraint key3 foreign key accountklasse(klasse) references klasse(id) on delete cascade on update cascade
);

#Schema Vorlage
create table term (
	id int auto_increment,
	termvorlage varchar(20) NOT NULL,
	level int(3),
	primary key (id)
);

create table konstanten (
	id int auto_increment,
	konstante varchar(1) NOT NULL,
	wert varchar(10) NOT NULL,
	primary key (id)
);

create table uebung (
	id int auto_increment,
	bezeichnung varchar(30) NOT NULL,
	ersteller int,
	anzahl int,
	von datetime,
	bis datetime,
	primary key (id)
);

create table aufgabe (
	id int auto_increment,
	bezeichnung varchar(50),
	typ enum('ausrechnen', 'runden', 'schaezten', 'vergleich') NOT NULL,
	term int NOT NULL,
	abweichung int,
	primary key (id),
	constraint key7 foreign key aufgabe(term) references term(id) on delete cascade on update cascade
);

#Festlegen von aufgabenspezifischen Festwerten für Variablen, werden von Superkonstanten überschrieben!
create table aufgabekonstante(
	aufgabe int,
	konstante int,
	primary key (aufgabe, konstante),
	constraint key8 foreign key aufgabekonstante(aufgabe) references aufgabe(id) on delete cascade on update cascade,
	constraint key9 foreign key aufgabekonstante(konstante) references konstanten(id) on delete cascade on update cascade
);

create table uebungaufgabe (
	uebung int,
	aufgabe int,
	primary key (uebung, aufgabe),
	constraint key10 foreign key uebungaufgabe(uebung) references uebung(id) on delete cascade on update cascade,
	constraint key11 foreign key uebungaufgabe(aufgabe) references aufgabe(id) on delete cascade on update cascade
);

create table uebungaccount (
	uebung int,
	account int,
	primary key (uebung, account),
	constraint key12 foreign key uebungaccount(uebung) references uebung(id) on delete cascade on update cascade,
	constraint key13 foreign key uebungaccount(account) references account(id) on delete cascade on update cascade
);

create table historie (
	id bigint auto_increment,
	aufgabe int NOT NULL,
	account int NOT NULL,
	rechnung varchar(30) NOT NULL,
	phpergebnis varchar(10) NOT NULL,
	eingabeergebnis varchar(10),
	richtig boolean,
	abweichung int,
	erreichteabweichung int,
	beginn datetime,
	beendet datetime,
	primary key(id),
	constraint key14 foreign key historie(aufgabe) references aufgabe(id) on delete cascade on update cascade,
	constraint key15 foreign key historie(account) references account(id) on delete cascade on update cascade
);

insert into account (username, password, email, rolle, vorname, nachname) values ('master', 'eb0a191797624dd3a48fa681d3061212', 'master@commander.de', 'admin', 'Mr T.', 'Bacon');
/*
CREATE USER 'crud'@'localhost' IDENTIFIED BY 'rw';
GRANT USAGE ON *.* TO 'crud'@'localhost';
GRANT SELECT, DELETE, INSERT, UPDATE  ON `kopfrechnen`.* TO 'crud'@'localhost';

CREATE USER 'login'@'localhost' IDENTIFIED BY 'login';
GRANT SELECT  ON TABLE `kopfrechnen`.`account` TO 'login'@'localhost';

CREATE USER 'lehrer'@'localhost' IDENTIFIED BY 'lehrer';
GRANT SELECT, INSERT, UPDATE, DELETE  ON `kopfrechnen`.* TO 'lehrer'@'localhost';

CREATE USER 'schueler'@'localhost' IDENTIFIED BY 'schueler';
GRANT USAGE ON *.* TO 'schueler'@'localhost';
GRANT SELECT  ON `kopfrechnen`.* TO 'schueler'@'localhost';
GRANT SELECT, UPDATE  ON TABLE `kopfrechnen`.`account` TO 'schueler'@'localhost';
GRANT SELECT, INSERT  ON TABLE `kopfrechnen`.`historie` TO 'schueler'@'localhost';

FLUSH PRIVILEGES;
*/





	
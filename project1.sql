create schema proj1;
use proj1;

DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `userid` INT auto_increment NOT NULL,
  `firstname` VARCHAR(45) NOT NULL,
  `lastname` VARCHAR(45) NOT NULL,
  `uemail` VARCHAR(45) UNIQUE NOT NULL,
  `password` VARCHAR(32) NOT NULL,
  `userstate` VARCHAR(45) NOT NULL,
   PRIMARY KEY (`userid`));
   
DROP TABLE IF EXISTS `notes`;
CREATE TABLE `notes` (
  `noteid` INT auto_increment NOT NULL,
  `notedesc` VARCHAR(255) NOT NULL,
  `userid` INT,
  `comments` varchar(45),
   PRIMARY KEY (`noteid`));

DROP TABLE IF EXISTS `friends`;
CREATE TABLE `friends` (
  `userid` INT NOT NULL,
  `fuserid` INT auto_increment NOT NULL,
  `status` VARCHAR(45) NOT NULL,
   PRIMARY KEY (`fuserid`, `userid`));

DROP TABLE IF EXISTS `notefilter`;
CREATE TABLE `notefilter` (
  `nid` INT auto_increment NOT NULL,
  `noteid` INT NOT NULL,
  `lat` DOUBLE NOT NULL,
  `longg` DOUBLE NOT NULL,
  `rad` INT NOT NULL,
   PRIMARY KEY (`nid`));

DROP TABLE IF EXISTS `userpref`;
CREATE TABLE `userpref` (
  `userid` INT NOT NULL,
  `state` VARCHAR(45) NOT NULL,
  `stime` time NOT NULL,
  `etime` time NOT NULL,
  `radius` INT NOT NULL,
   PRIMARY KEY (`state`, `userid`));
   
DROP TABLE IF EXISTS `location`;
CREATE TABLE `location` (
  `lid` INT auto_increment NOT NULL,
  `userid` INT NOT NULL,
  `latitude` DOUBLE NOT NULL,
  `longitude` DOUBLE NOT NULL,
  `ts` datetime NOT NULL,
   PRIMARY KEY (`lid`, `userid`));

DROP TABLE IF EXISTS `tag`;
CREATE TABLE `tag` (
  `tagid` INT auto_increment NOT NULL,
  `tagname` VARCHAR(45) UNIQUE NOT NULL,
   PRIMARY KEY (`tagid`));

DROP TABLE IF EXISTS `utag`;
CREATE TABLE `utag` (
  `userid` INT NOT NULL,
  `tagid` INT NOT NULL,
  `state` VARCHAR(45) NOT NULL,
   PRIMARY KEY (`userid`, `state`, `tagid`));

DROP TABLE IF EXISTS `ntag`;
CREATE TABLE `ntag` (
  `nid` INT NOT NULL,
  `tagid` INT NOT NULL,
   PRIMARY KEY (`nid`, `tagid`));

DROP TABLE IF EXISTS `scheduler`;
CREATE TABLE `scheduler` (
  `sid` INT auto_increment NOT NULL,
  `nid` INT NOT NULL,
  `fromdate` DATE NOT NULL,
  `todate` DATE NOT NULL,
   PRIMARY KEY (`sid`));
   
DROP TABLE IF EXISTS `days`;
CREATE TABLE `days` (
  `dayid` INT auto_increment NOT NULL,
  `sid` INT NOT NULL,
  `fromtime` TIME NOT NULL,
  `totime` TIME NOT NULL,
  `daay` VARCHAR(45) NOT NULL,
   PRIMARY KEY (`dayid`));

DROP TABLE IF EXISTS `comm`;
CREATE TABLE `comm` (
  `cid` INT auto_increment NOT NULL,
  `noteid` INT NOT NULL,
  `userid` INT NOT NULL,
  `commentdesc` varchar(255),
  `timestampts` datetime NOT NULL,
   PRIMARY KEY (`cid`));

ALTER TABLE notes ADD FOREIGN KEY (userid) REFERENCES user(userid) ON DELETE CASCADE;
ALTER TABLE friends ADD FOREIGN KEY (userid) REFERENCES user(userid) ON DELETE CASCADE;
ALTER TABLE friends ADD FOREIGN KEY (fuserid) REFERENCES user(userid) ON DELETE CASCADE;
ALTER TABLE userpref ADD FOREIGN KEY (userid) REFERENCES user(userid) ON DELETE CASCADE;
ALTER TABLE location ADD FOREIGN KEY (userid) REFERENCES user(userid)ON DELETE CASCADE;
ALTER TABLE notefilter ADD FOREIGN KEY (noteid) REFERENCES notes(noteid)ON DELETE CASCADE;
ALTER TABLE utag ADD FOREIGN KEY (tagid) REFERENCES tag(tagid) ON DELETE CASCADE;
ALTER TABLE utag ADD FOREIGN KEY (userid) REFERENCES userpref(userid) ON DELETE CASCADE;
ALTER TABLE utag ADD FOREIGN KEY (state) REFERENCES userpref(state) ON DELETE CASCADE;
ALTER TABLE ntag ADD FOREIGN KEY (tagid) REFERENCES tag(tagid) ON DELETE CASCADE;
ALTER TABLE ntag ADD FOREIGN KEY (nid) REFERENCES notefilter(nid) ON DELETE CASCADE;
ALTER TABLE scheduler ADD FOREIGN KEY (nid) REFERENCES notefilter(nid) ON DELETE CASCADE;
ALTER TABLE days ADD FOREIGN KEY (sid) REFERENCES scheduler(sid) ON DELETE CASCADE;
ALTER TABLE comm ADD FOREIGN KEY (userid) REFERENCES user(userid) ON DELETE CASCADE;
ALTER TABLE comm ADD FOREIGN KEY (noteid) REFERENCES notes(noteid) ON DELETE CASCADE;

INSERT INTO `user` VALUES (1, 'Sandeep', 'Nandimandalam', 'sn2610','sandeep', 'lunch');
INSERT INTO `user` VALUES (2, 'S', 'N', 'SN','SN', 'lunch');
INSERT INTO `friends` VALUES (1,2, 'accepted');

INSERT INTO notes(noteid,notedesc,userid,comments) VALUES (1,"Thai specials!",1,"allowed");
INSERT INTO notefilter(nid,noteid,lat,longg,rad) VALUES (1,1,1,1,1);
INSERT INTO userpref(userid,state,stime,etime,radius) VALUES (1,'lunch','5:00','8:00',1);
INSERT INTO tag(tagid,tagname) VALUES (1,"#food");
INSERT INTO utag(userid,tagid,state) VALUES (1,1,'lunch');
INSERT INTO ntag(nid,tagid) VALUES (1,1);
INSERT INTO location(lid,userid,latitude,longitude,ts) VALUES (1,1,0.5,0.5,now());
INSERT INTO scheduler(sid,nid,fromdate,todate) VALUES (1,1,'2018-11-22','2018-11-24');
INSERT INTO days(dayid,sid,fromtime,totime,daay) VALUES (1,1,'05:00:00', '08:00:00', 'everyday');
INSERT INTO comm(cid,userid,noteid,commentdesc,timestampts) VALUES (1,1,1,"Delicious", now());


INSERT INTO `user` VALUES (3, 'Anvita', 'Marla', 'am9435','anvita', 'dinner');
INSERT INTO `user` VALUES (4, 'A', 'M', 'AM','am', 'dinner');
INSERT INTO `friends` VALUES (3,4, 'declined');

INSERT INTO `user` VALUES (5, 'Micheal', 'Scott', 'MC','mc', 'dinner');
INSERT INTO `friends` VALUES (3,5, 'accepted');

INSERT INTO notes(noteid,notedesc,userid,comments) VALUES (2,"5 star hotels that serve shrimp",3,"notallowed");
INSERT INTO notefilter(nid,noteid,lat,longg,rad) VALUES (2,2,1,1,1);
INSERT INTO userpref(userid,state,stime,etime,radius) VALUES (3,'dinner','7:00','10:00',1);
INSERT INTO tag(tagid,tagname) VALUES (2,"#dinner");
INSERT INTO utag(userid,tagid,state) VALUES (3,2,'dinner');
INSERT INTO ntag(nid,tagid) VALUES (2,2);
INSERT INTO location(lid,userid,latitude,longitude,ts) VALUES (2,3,0.5,0.5,now());
INSERT INTO scheduler(sid,nid,fromdate,todate) VALUES (2,2,'2018-10-22','2018-10-24');
INSERT INTO days(dayid,sid,fromtime,totime,daay) VALUES (2,2,'07:00:00', '10:00:00', 'sunday');


INSERT INTO `user` VALUES (6, 'Jim', 'Halpert', 'jim@32','jim', 'breakfast');
INSERT INTO `user` VALUES (7, 'Pam', 'Beesly', 'pam@23','pb', 'breakfast');
INSERT INTO `friends` VALUES (6,7, 'accepted');

INSERT INTO notes(noteid,notedesc,userid,comments) VALUES (3,"hotels that serve only eggs",6,"notallowed");
INSERT INTO notefilter(nid,noteid,lat,longg,rad) VALUES (3,3,3,3,6);
INSERT INTO userpref(userid,state,stime,etime,radius) VALUES (6,'breakfast','6:00','8:00',6);
INSERT INTO tag(tagid,tagname) VALUES (3,"#breakfast");
INSERT INTO utag(userid,tagid,state) VALUES (6,3,'breakfast');
INSERT INTO ntag(nid,tagid) VALUES (3,3);
INSERT INTO location(lid,userid,latitude,longitude,ts) VALUES (3,6,1.5,1.5,now());
INSERT INTO scheduler(sid,nid,fromdate,todate) VALUES (3,3,'2018-08-22','2018-09-24');
INSERT INTO days(dayid,sid,fromtime,totime,daay) VALUES (3,3,'01:00:00', '03:00:00', 'saturday');


INSERT INTO `user` VALUES (8, 'Dwight', 'Schrute', 'ds@54','Dwight', 'lunch');
INSERT INTO `user` VALUES (9, 'Angela', 'Martin', 'am@45','Angela', 'lunch');
INSERT INTO `friends` VALUES (8,9, 'accepted');

INSERT INTO notes(noteid,notedesc,userid,comments) VALUES (4,"pet friendly restaurents",8,"allowed");
INSERT INTO notefilter(nid,noteid,lat,longg,rad) VALUES (4,4,2,2,4);
INSERT INTO userpref(userid,state,stime,etime,radius) VALUES (8,'lunch','12:00','1:00',4);
INSERT INTO tag(tagid,tagname) VALUES (4,"#lunch");
INSERT INTO utag(userid,tagid,state) VALUES (8,4,'lunch');
INSERT INTO ntag(nid,tagid) VALUES (4,4);
INSERT INTO location(lid,userid,latitude,longitude,ts) VALUES (4,8,1,1,now());
INSERT INTO scheduler(sid,nid,fromdate,todate) VALUES (4,4,'2018-06-22','2018-10-24');
INSERT INTO days(dayid,sid,fromtime,totime,daay) VALUES (4,4,'12:00:00', '01:00:00', 'monday');
INSERT INTO comm(cid,userid,noteid,commentdesc,timestampts) VALUES (2,8,4,"Amazing food", now());
CREATE TABLE IF NOT EXISTS `Shifts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(64) NOT NULL,
  `checkin` datetime NOT NULL,
  `checkout` datetime NOT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`username`) REFERENCES Users(`username`)
) AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `SessionID` (
  `session_id` varchar(100) NOT NULL,
  `username` varchar(64) NOT NULL,
  `ip` varchar(45) NOT NULL,
  `activity` datetime NOT NULL,
  PRIMARY KEY (`session_id`),
  FOREIGN KEY (`username`) REFERENCES Users(`username`)
) ;

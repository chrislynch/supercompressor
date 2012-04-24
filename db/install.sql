CREATE TABLE `sc_index` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Type` varchar(255) DEFAULT NULL,
  `URL` varchar(1000) DEFAULT NULL,
  `Status` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`ID`),
  UNIQUE KEY `URL_UNIQUE` (`URL`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

CREATE TABLE `sc_data` (
  `ID` int(11) NOT NULL,
  `Field` varchar(255) NOT NULL,
  `Value` text,
  PRIMARY KEY (`ID`,`Field`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
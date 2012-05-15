CREATE TABLE `sc_index` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Type` varchar(255) DEFAULT NULL,
  `URL` varchar(1000) DEFAULT NULL,
  `Status` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`ID`),
  UNIQUE KEY `URL_UNIQUE` (`URL`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;

CREATE TABLE `sc_data` (
  `ID` int(11) NOT NULL,
  `Field` varchar(255) NOT NULL,
  `Value` text,
  PRIMARY KEY (`ID`,`Field`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `sc_search` (
  `ID` int(11) NOT NULL,
  `SearchText` text NOT NULL,
  PRIMARY KEY (`ID`),
  FULLTEXT KEY `sc_search_fulltext` (`SearchText`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

CREATE TABLE  `sc_link` (
  `fromID` int(11) NOT NULL,
  `toID` int(11) NOT NULL,
  PRIMARY KEY (`fromID`,`toID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


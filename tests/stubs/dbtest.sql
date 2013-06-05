CREATE TABLE IF NOT EXISTS `jos_foftest_foobaraliases` (
  `id_foobar_aliases` int(11) NOT NULL AUTO_INCREMENT,
  `fo_title` varchar(100) NOT NULL,
  `fo_slug` varchar(100) NOT NULL,
  `fo_enabled` tinyint(4) NOT NULL,
  `fo_ordering` tinyint(4) NOT NULL,
  `fo_created_by` int(11) NOT NULL,
  `fo_created_on` datetime NOT NULL,
  `fo_modified_by` int(11) NOT NULL,
  `fo_modified_on` datetime NOT NULL,
  `fo_locked_by` int(11) NOT NULL,
  `fo_locked_on` datetime NOT NULL,
  PRIMARY KEY (`id_foobar_aliases`)
);

CREATE TABLE IF NOT EXISTS `jos_foftest_foobars` (
  `foftest_id_foobar` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(100) NOT NULL,
  `slug` varchar(100) NOT NULL,
  `enabled` tinyint(4) NOT NULL,
  `ordering` tinyint(4) NOT NULL,
  `created_by` int(11) NOT NULL,
  `created_on` datetime NOT NULL,
  `modified_by` int(11) NOT NULL,
  `modified_on` datetime NOT NULL,
  `locked_by` int(11) NOT NULL,
  `locked_on` datetime NOT NULL,
  PRIMARY KEY (`foftest_id_foobar`)
);

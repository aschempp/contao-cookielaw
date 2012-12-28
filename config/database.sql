-- **********************************************************
-- *                                                        *
-- * IMPORTANT NOTE                                         *
-- *                                                        *
-- * Do not import this file manually but use the Contao    *
-- * install tool to create and maintain database tables!   *
-- *                                                        *
-- **********************************************************

--
-- Table `tl_page`
--

CREATE TABLE `tl_page` (
  `cookielawEnabled` char(1) NOT NULL default '',
  `cookielawBody` text NULL,
  `cookielawButtons` varchar(255) NOT NULL default '',
  `cookielawRecipient` varchar(255) NOT NULL default '',
  `cookielawBlocked` blob NULL,
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

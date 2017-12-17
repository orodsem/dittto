-- Add some users
INSERT INTO `dittto_user` (`id`, `username`, `username_canonical`, `email`, `email_canonical`, `enabled`, `password`, `roles`, `firstname`, `lastname`) VALUES (null, 'admin', 'admin', 'a@a.com', 'a@a.com', '1', '$2y$13$Tq7EjIrki6iFi1rhy8hAmedbyjB2YyT2bo1vQZTgJF/NTYp8A3XRa', 'a:1:{i:0;s:16:\"ROLE_SUPER_ADMIN\";}', 'admin', 'admin');
INSERT INTO `dittto_user` (`id`, `username`, `username_canonical`, `email`, `email_canonical`, `enabled`, `password`, `roles`, `firstname`, `lastname`) VALUES (null, 'teacher', 'teacher', 't@d.com', 't@d.com', '1', '$2y$13$q34V4X2AL1zrQ0Sc62rA0OEraVzALbjedBefPCdwlOfZQ3CzxzL86', 'a:1:{i:0;s:12:\"ROLE_TEACHER\";}', 'teacher', 'smith');
INSERT INTO `dittto_user` (`id`, `username`, `username_canonical`, `email`, `email_canonical`, `enabled`, `password`, `roles`, `firstname`, `lastname`) VALUES (null, 'student', 'student', 's@c.com', 's@c.com', '1', '$2y$13$soz.AjkGmyFEcq/vahs2Bet9eDUFqek0kur0S9eBYQ/2y8azdZek2', 'a:1:{i:0;s:12:\"ROLE_STUDENT\";}', 'student', 'smith');

-- add some criteria
INSERT INTO `dittto_criteria` (`title`, `description`, `point`, `image`) VALUES ('red', 'red', '0', 'dev/img/criteria/red.png');
INSERT INTO `dittto_criteria` (`title`, `description`, `point`, `image`) VALUES ('purple', 'red', '0', 'dev/img/criteria/purple.png');

-- add Dittto School
INSERT INTO `dittto_school` (`name`) VALUES ('Dittto');

-- add a Campus
INSERT INTO `dittto_campus` (`school_id`, `name`) VALUES ('1', 'Melbourne');

-- add some Year Levels
INSERT INTO `dittto_year_level` (`campus_id`, `name`) VALUES ('1', 'Year 1');
INSERT INTO `dittto_year_level` (`campus_id`, `name`) VALUES ('1', 'Year 2');

-- add some Groups (Classes)
INSERT INTO `dittto_group` (`year_level_id`, `name`) VALUES ('1', 'Class 1A');
INSERT INTO `dittto_group` (`year_level_id`, `name`) VALUES ('1', 'Class 1B');
INSERT INTO `dittto_group` (`year_level_id`, `name`) VALUES ('2', 'Class 2A');
INSERT INTO `dittto_group` (`year_level_id`, `name`) VALUES ('2', 'Class 2B');


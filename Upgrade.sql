--
-- Issue: #14
--
ALTER TABLE `news_posts` CHANGE `content` `content` TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL;
ALTER TABLE `news_posts` CHANGE `view` `view` INT(11) NOT NULL DEFAULT '0';

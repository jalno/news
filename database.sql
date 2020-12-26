
CREATE TABLE `news_posts` (
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`title` varchar(255) COLLATE utf8_persian_ci NOT NULL,
	`date` int(11) NOT NULL,
	`description` varchar(255) COLLATE utf8_persian_ci NOT NULL,
	`author` int(11) NOT NULL,
	`content` text COLLATE utf8_persian_ci NOT NULL,
	`image` varchar(255) COLLATE utf8_persian_ci DEFAULT NULL,
	`view` int(11) NOT NULL,
	`status` tinyint(4) NOT NULL,
	PRIMARY KEY (`id`),
	KEY `author` (`author`),
	CONSTRAINT `news_posts_ibfk_1` FOREIGN KEY (`author`) REFERENCES `userpanel_users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_persian_ci;
CREATE TABLE `news_comments` (
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`post` int(11) NOT NULL,
	`reply` int(11) DEFAULT NULL,
	`email` varchar(255) COLLATE utf8_persian_ci NOT NULL,
	`name` varchar(255) COLLATE utf8_persian_ci NOT NULL,
	`date` int(10) unsigned NOT NULL,
	`text` text COLLATE utf8_persian_ci NOT NULL,
	`status` tinyint(4) NOT NULL,
	PRIMARY KEY (`id`),
	KEY `progress` (`post`),
	KEY `reply` (`reply`),
	CONSTRAINT `news_comments_ibfk_1` FOREIGN KEY (`post`) REFERENCES `news_posts` (`id`) ON DELETE CASCADE,
	CONSTRAINT `news_comments_ibfk_2` FOREIGN KEY (`reply`) REFERENCES `news_comments` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_persian_ci;

CREATE TABLE `news_files` (
 `id` int(11) NOT NULL AUTO_INCREMENT,
 `post` int(11) DEFAULT NULL,
 `file` varchar(255) NOT NULL,
 `name` varchar(255) NOT NULL,
 `size` int(10) unsigned NOT NULL,
 `md5` varchar(32) NOT NULL,
 PRIMARY KEY (`id`),
 KEY `md5` (`md5`),
 KEY `post` (`post`),
 CONSTRAINT `news_files_ibfk_1` FOREIGN KEY (`post`) REFERENCES `news_posts` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

INSERT INTO `userpanel_usertypes_permissions` (`type`, `name`) VALUES
(1, 'news_add'),
(1, 'news_comments_delete'),
(1, 'news_comments_edit'),
(1, 'news_comments_list'),
(1, 'news_delete'),
(1, 'news_edit'),
(1, 'files_upload'),
(1, 'files_delete'),
(1, 'news_list');

Nette Web Project
=================

This is a simple, skeleton application using the [Nette](https://nette.org). This is meant to
be used as a starting point for your new projects.

[Nette](https://nette.org) is a popular tool for PHP web development.
It is designed to be the most usable and friendliest as possible. It focuses
on security and performance and is definitely one of the safest PHP frameworks.

If you like Nette, **[please make a donation now](https://nette.org/donate)**. Thank you!


Requirements
------------

- Web Project for Nette 3.0 requires at least PHP 8.0


Installation
------------

The best way to install Web Project is using Composer. If you don't have Composer yet,
download it following [the instructions](https://doc.nette.org/composer). Then use command:

	composer create-project nette/web-project path/to/install
	cd path/to/install


Make directories `temp/` and `log/` writable.


Web Server Setup
----------------

The simplest way to get started is to start the built-in PHP server in the root directory of your project:

	php -S localhost:8000 -t www

Then visit `http://localhost:8000` in your browser to see the welcome page.

For Apache or Nginx, setup a virtual host to point to the `www/` directory of the project and you
should be ready to go.

**It is CRITICAL that whole `app/`, `log/` and `temp/` directories are not accessible directly
via a web browser. See [security warning](https://nette.org/security-warning).**

Installation
------------

The best way to install Web Project is using Composer. If you don't have Composer yet,
download it following [the instructions](https://doc.nette.org/composer). Then use command:

	composer create-project venca-x/web-project path/to/install
	cd path/to/install
	npm install
	
Run grund init tasks:
```
gulp upgrade
```

Make directories `temp/` and `log/` writable.


Used technologies
----------------
* gulp
* npm
* nette/tester
* Sass
* Bootstrap v5
* FontAwesome
* Jquery
* Minifi CSS and JS
* nette-form-renderer
* ftp-deployment
* [Naja](https://github.com/jiripudil/Naja) (JS client-side AJAX library)

### Basic commain in Grunt
**deploy-prod**
Deploy files to prod server

**deploy-test**
Deploy files to test server

### Mysql table for login
```
CREATE TABLE `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(128) COLLATE utf8_czech_ci NOT NULL,
  `password` varchar(255) COLLATE utf8_czech_ci NOT NULL,
  `role` varchar(64) COLLATE utf8_czech_ci DEFAULT NULL,
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;
--
-- Data for table `user`
--

INSERT INTO `user` (`id`, `email`, `password`, `role`) VALUES
(null, 'vencax@gmail.com', 'xxxxx', 'admin');
```

### Coding style
In superior directory instal this packages. This CS packages are in superior, because are sharing with multiple procets.
```
composer create-project nette/code-checker nette-code-checker
composer create-project nette/coding-standard nette-coding-standard
```

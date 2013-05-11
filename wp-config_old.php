<?php
/**
 * The base configurations of the WordPress.
 *
 * This file has the following configurations: MySQL settings, Table Prefix,
 * Secret Keys, WordPress Language, and ABSPATH. You can find more information
 * by visiting {@link http://codex.wordpress.org/Editing_wp-config.php Editing
 * wp-config.php} Codex page. You can get the MySQL settings from your web host.
 *
 * This file is used by the wp-config.php creation script during the
 * installation. You don't have to use the web site, you can just copy this file
 * to "wp-config.php" and fill in the values.
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'wordpress');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', '');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         'LJmzS]vW+s<w{xg&dC3)>N.0Y$4CIrPg59{zu8d^UnJCn3W*a~w`~1GA):zhqno ');
define('SECURE_AUTH_KEY',  ';P0eQ X^5hIZ;:5hmj:Gz:&owpIzL+9h,D6v1+1.:a+9qF`_Tpu~A)GI65[YKjfc');
define('LOGGED_IN_KEY',    ']CbL0ZkIhQ$e,VADwE:J&<l#=c*Ea2e@bhF~$+I{*zry7qGr/)mG6t*qG*VYfBq1');
define('NONCE_KEY',        '.I3Hg$#YSu]V`?Jc+T!bZ(=_1h!Qck!QsjHkq{IhxX*B$L<l*;?P_w* FY(yx<=S');
define('AUTH_SALT',        'I.b=oiJJD_1fk{(|*q%)aK*/-IET?ecZQ`viGb%XVd|=W5Yh+2zATgIn(jcgmRTo');
define('SECURE_AUTH_SALT', 'o<xatTNoRyi&} U~{NwP}BJaT(Zh7iT3Y1QX!HqZamu8bm{tadjF4aAw{<9|uT67');
define('LOGGED_IN_SALT',   'OU+NvK*6@$#~Dup.k&4Mdj@Y6Vz}j&PFZHlWV%f,0m&ujGqG0!W6d~|ni,hlmgIl');
define('NONCE_SALT',       '%de(Tu-#cWQDHs&Je}sN=q8q5MB,Wg]^,/J!iDJ9&m4<Pa;kZK99J&KVgox}./gl');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

/**
 * WordPress Localized Language, defaults to English.
 *
 * Change this to localize WordPress. A corresponding MO file for the chosen
 * language must be installed to wp-content/languages. For example, install
 * de_DE.mo to wp-content/languages and set WPLANG to 'de_DE' to enable German
 * language support.
 */
define('WPLANG', '');

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 */
define('WP_DEBUG', false);

/**
 * Network Mapping
 */

/* That's all, stop editing! Happy blogging. */
define('MULTISITE', true);
define('SUBDOMAIN_INSTALL', true);
define('DOMAIN_CURRENT_SITE', 'dev.wordpress.com');
define('PATH_CURRENT_SITE', '/');
define('SITE_ID_CURRENT_SITE', 1);
define('BLOG_ID_CURRENT_SITE', 1);

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');

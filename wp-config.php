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
define('DB_NAME', 'samplew.vtscat.com');

/** MySQL database username */
define('DB_USER', 'vtscatw');

/** MySQL database password */
define('DB_PASSWORD', 'tinhanh123');

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
define('AUTH_KEY',         '@Gt==73:+iRFElS)xF)yM+;V-$<BsU,)N;A^n_&|ikF3xkRJD+P/>3si`J8f:-Df');
define('SECURE_AUTH_KEY',  '+%_WA$~}AITwddc-=~} GRi-sr;Mnz_#o1eCSfTE#n$7jj=7:-4aF)I3;+@0=%fu');
define('LOGGED_IN_KEY',    'p! 9^pPk+2P~^rE/<xk:H~)*VA={cBarOzB7Iw|gvgf.IVmg}|xg3~+9?-;v|06p');
define('NONCE_KEY',        'm)hgUAHTX2-&uV)Qc=H:>;t!9$4mvm~_V%$>w^ILmAv/|l:dTI~!ZG%J*0Bg/Q+I');
define('AUTH_SALT',        '02=EoY78D~S5rqSnd(xs,3s!AtRlws:|%(*LINk$#r4MS{o|aWun`J5lMK6rdrlT');
define('SECURE_AUTH_SALT', '8Gp{jZ%1;[4nWRW+oVxWJ0CF?1#^<r~?&6ByYGRfww#(Z[ ],]^R|?{-[5l.GQdw');
define('LOGGED_IN_SALT',   'e@{,U]wRnK2BY--Pc&_39r?w<Z+*Z[(J273QxY-~`Qm|ux.W|ap(WA85}m@XZ|T1');
define('NONCE_SALT',       '/N{WmV8qR-|[l$c5b}*ma<])O{bb,Na&njT^,=4F{h{MW:L^J}H*40RK<Rh1Az?R');

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

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');

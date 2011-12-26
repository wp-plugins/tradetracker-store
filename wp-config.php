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
define('DB_NAME', 'vakantieoplossing');

/** MySQL database username */
define('DB_USER', 'vakantieoplossing');

/** MySQL database password */
define('DB_PASSWORD', 'fan19841');

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
define('AUTH_KEY',         'e@R+Wm|PmS~Vhkk`oW@0&.wCrO.BA@08{ktCH@|d3ndn+z|0z{qebDzxmB9ZwHR:');
define('SECURE_AUTH_KEY',  'j$oz+~D_|EQ1P]@m*EchbMu|T(DWUQ0{YJYNe`)71wRAc-+P=K lW]lr,y<,O{QM');
define('LOGGED_IN_KEY',    '5VHk1_(+Q LbQp$(z2,EF(Vc7WUOdD/GpfguGAQ f*~hCD]RX=^&<h5RZ+:cT#e~');
define('NONCE_KEY',        'deE96})kS[eY?lO1kEZ}j6+7$GeJ9POucgvEM4Jy|c@=KpTx?[3Damq4!V4aW2TN');
define('AUTH_SALT',        '(5h^)vg:4EEewfR>qew[wtW!=bbIb;ns<D7E(xG-+M,a6:q!/ux9_z*rqkY_H?BD');
define('SECURE_AUTH_SALT', 'Q+Ky4G`+zWaa-b+xvc/g%n?!>gR_kqb#8Y*nES~lAgi1mmcw{8D:1Q mP8@X:}?8');
define('LOGGED_IN_SALT',   ')bP3jj1-;v#ij<?|;BrSoe=`|wXqQz^,^+OTCzaUStyX1Ft~*b<kePlRQueWV_n6');
define('NONCE_SALT',       '2z]o3.|haPgih9>1a#$;V?il|A${;5YquX}36Xq 8- u61Y8h=H5v}Z8Be;0:fu ');

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
 * Change this to localize WordPress.  A corresponding MO file for the chosen
 * language must be installed to wp-content/languages. For example, install
 * de.mo to wp-content/languages and set WPLANG to 'de' to enable German
 * language support.
 */
define ('WPLANG', 'en_US');

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 */
define('WP_DEBUG', true);
define('WP_DEBUG_LOG', true);
define('WP_DEBUG_DISPLAY', false);
@ini_set('display_errors',0);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');

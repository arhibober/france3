<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the
 * installation. You don't have to use the web site, you can
 * copy this file to "wp-config.php" and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * MySQL settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://codex.wordpress.org/Editing_wp-config.php
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'france2');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', '');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8mb4');

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
define('AUTH_KEY',         '-B@/YORaf0/zp5tN/OdVq5Mq-8{i~(53 4,?V1Tbq;lHxa=<Xm*b*nO0)q>8/BS]');
define('SECURE_AUTH_KEY',  'D19]z:@LhEG8U:S lRFl3Mbs)n3VL#BoL:nk&dy|TWiq9MaRtJ1DifvZuE#.Q.e9');
define('LOGGED_IN_KEY',    '`)+<]Ehpy<TXG[:Oh0H5RISr~s8gj-hL}bPq(baH}UE-s:$UxI_H nu6{(bV+TZu');
define('NONCE_KEY',        'f)&;AGu^C/`%VWD33r8<3gT(-3/ogMp(wOn0RBh.5ek!Wj))`FTZ-|rjF8rWY5!y');
define('AUTH_SALT',        '6nfW7+7j<+$Ar6(3.iHq0XdI7X~KlnDami:R0aA?xS`4E@)`zZ~!tXY?Mz{_ -En');
define('SECURE_AUTH_SALT', 'h.JTUYilS/yo%]j/b zt0a7~T>.*AS<(+xOHen=gxZ3yGwH=Y+CGz</T@VmP2/BT');
define('LOGGED_IN_SALT',   'kr~>11|I@nVP&VP[R{LG|LUe]gD@gXzUM&jsv]Ht%0%o:mIr|]@E>yP<%Y$[~HS+');
define('NONCE_SALT',       'J6[A~Wyd p1i2xsi_sFWS0e/_O}4vKwnse6jmaw($_^h,:Ht?@1#cow7d7 Y{2FL');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the Codex.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
ini_set('log_errors','On');
ini_set('display_errors','Off');
ini_set('error_reporting', E_ALL );
define('WP_DEBUG', false);
define('WP_DEBUG_LOG', true);
define('WP_DEBUG_DISPLAY', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');

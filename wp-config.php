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
define('DB_PASSWORD', 'Alexander#1');

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
define('AUTH_KEY',         'y/w=`u 4[miG_hv>P(JB%6_a`nV*G*e+6.-J*Su.[MsT(HF#$~M{iF?yx8/jX}G,');
define('SECURE_AUTH_KEY',  'O(iqR5=6{_||Et4}PM<`QRx>BtQz @j%3),[kPT?6HP:,yXy!| aaN3R?_w{<q2$');
define('LOGGED_IN_KEY',    '<>d- Y?jY^~sF$yL%6U5$@0Gx8B`>Pm[%: (%P4^hUf5o.A?WPDW=~@`W,D4Z/}K');
define('NONCE_KEY',        '/H/3heq+B9L2{/g_,l%YL=<(oSO]28JATbly5>*0yeqhPr_s5oK{<KhEMd-T,F|s');
define('AUTH_SALT',        'F>d^8mtY`X.{THHW,n2lyA<aJ5o6)FpL*uvm5nPn`RR/fdDLq*2sG L{lnG`mw~C');
define('SECURE_AUTH_SALT', 'NtDJuW8pp0%HetoCTtV[8xt*u} _vX4+ea]fD28V}|W scl+4?`Sppsf_(BeCxy^');
define('LOGGED_IN_SALT',   '}Gzo3^>crwUw|7GT(a@fb11fL/&f[5`62IdZ^@j6&>lwHQ|47}>PhCylCzU4-fqR');
define('NONCE_SALT',       'p~);n%:fSl-ln8m!>l~`3V<YFtrV21c,a>faSNI8Y%0Qz{d1iE@@- 9z0A/9YiNE');

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

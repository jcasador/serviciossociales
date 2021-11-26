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
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', '6902213_wp_mpl3y' );

/** MySQL database username */
define( 'DB_USER', 'wp_qx674' );

/** MySQL database password */
define( 'DB_PASSWORD', 'ppCi1eNO%1%5*eOq' );

/** MySQL hostname */
define( 'DB_HOST', 'PMYSQL113.dns-servicio.com:3306' );

/** Database Charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8' );

/** The Database Collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY', 'fT4!n0/oO1y(/&qMo50R~v67Qz~:+:-#];e6R4r&;)x4-+&9~&l4Yg5ny]HIS0dS');
define('SECURE_AUTH_KEY', 'l~vT%K8rVMw1mlBsX9*kKs9/Jm4r;B03W__+!;;[7;mG5/jzg33Z84PS;)+@_@2n');
define('LOGGED_IN_KEY', '9r4f:Ol*q0-%gR41YA;;9f[nu#f0*8BkQ@P#;g0&+7vGRZ!7PcP_L1ccE66Z0qS)');
define('NONCE_KEY', '4Tt1_yU9t2krX9o&x)S:]Oa]ex4V*96%UJ%k0b9k]22mD|:RbT!AXDBfM3H!E|2)');
define('AUTH_SALT', 'k*5nm1VgQ1*893Aq%0[mg0Ho%6I-4Lk0Z31FwBi(%h4s;]w9_3n2o32|7IQ2Z1X#');
define('SECURE_AUTH_SALT', '7OHP;%C_+!|v%Z/m!L!%&j91RwN4F3406DQMG1rszpQZa;l@J5W*/4ZNf+H1s6e5');
define('LOGGED_IN_SALT', '(4:C(t;*Hj+JZD*[s#@B|QW@DA8d-PAsWnc/&1)!I8-2kf5/K3jd/Rv]z8706_3(');
define('NONCE_SALT', 'b1S/:n8g6jx|M;VM2:(QCB1kI6!U(sIJUE:/*8uEw0~;Hvq948b(T9S[53&YoV-0');

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = '4Wd7f3i3_';


define('WP_ALLOW_MULTISITE', true);
/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', dirname( __FILE__ ) . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';

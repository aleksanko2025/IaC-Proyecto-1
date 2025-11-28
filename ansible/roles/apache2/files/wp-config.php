<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the website, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://developer.wordpress.org/advanced-administration/wordpress/wp-config/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'wordpress_db' );

/** Database username */
define( 'DB_USER', 'wordp_user' );

/** Database password */
define( 'DB_PASSWORD', 'wordp_pass' );

/** Database hostname */
define( 'DB_HOST', 'mariadb.example.org' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The database collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication unique keys and salts.
 *
 * Change these to different unique phrases! You can generate these using
 * the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}.
 *
 * You can change these at any point in time to invalidate all existing cookies.
 * This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         ']YqW~)`So=Zf&;l$H*pv!S;t5G67PdD5~{V[f1%;_XLn(mht]R{g,)Fh>$}gKvj7' );
define( 'SECURE_AUTH_KEY',  '[eK@vnW&b>`ywH)GJwhuhB[l67?@qU_gnI?.d(*Xd!#?A%odKMr>pK;{ddM{eY(V' );
define( 'LOGGED_IN_KEY',    '8=U`S1@8#&MXE) WW?uj;&vg d#RWX66%3?Vp=T,>wqm.-I](<MJ@>N@?kB+wKa4' );
define( 'NONCE_KEY',        '{|}T01THaOT _*K%5f}Gks%qSR8q2<FVo{f8OQwEr@A[A(+{*m)-w%cwuO?b=#ug' );
define( 'AUTH_SALT',        '@mwaV~,3}&%:]yEwo~S}(gJHH48jHnG?*(],Z2`XLbG5}Hopmx:dc,-|rEC|*vCS' );
define( 'SECURE_AUTH_SALT', 'N9n62BteZU!d0!:lF}z-=;3Y`Jz|?A}[IZ6itWs^AdVOo5j4o>K4mMS}AN[~0@%|' );
define( 'LOGGED_IN_SALT',   ')PX8;Ih<[fX[t{!?ToT?Qwg!|?p4Yk!upivK5[7-@Pq]hm/[T~AghLjzx6DlYTH<' );
define( 'NONCE_SALT',       'ln3/SP}K`D,K`772HiT;7G#3-0?zd$)<}7^.JLvV v[2/VH`SEPz}kzv =siLB9|' );

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 *
 * At the installation time, database tables are created with the specified prefix.
 * Changing this value after WordPress is installed will make your site think
 * it has not been installed.
 *
 * @link https://developer.wordpress.org/advanced-administration/wordpress/wp-config/#table-prefix
 */
$table_prefix = 'wp_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the documentation.
 *
 * @link https://developer.wordpress.org/advanced-administration/debug/debug-wordpress/
 */
define( 'WP_DEBUG', false );

/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';

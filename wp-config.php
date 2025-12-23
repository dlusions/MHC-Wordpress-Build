<?php
# Database Configuration
define( 'DB_NAME', 'wp_mhc2025dev' );
define( 'DB_USER', 'mhc2025dev' );
define( 'DB_PASSWORD', 'UrOyLuw14GkTKQU_0koa' );
define( 'DB_HOST', '127.0.0.1:3306' );
define( 'DB_HOST_SLAVE', '127.0.0.1:3306' );
define('DB_CHARSET', 'utf8');
define('DB_COLLATE', 'utf8_unicode_ci');
$table_prefix = 'wp_';

# Security Salts, Keys, Etc
define('AUTH_KEY',         '7aFVz%B)bneljRg@*F,_+WN-dq7*F2sQb1c.kFY7l,Ej,an52c7_*I._x=_*#tGW');
define('SECURE_AUTH_KEY',  '$T=Aya$6UsXAdTXy4qeEXH6CC(nXmDKmgk^3zgkk9Zjj5#HFwxUObW=uW!!wnGfy');
define('LOGGED_IN_KEY',    'Od1O@7I3qC=Q#W~mz5fjY3Z4l?d2vIOt39b?J,rOjc&fN3D~ET5ttYtBX79SN#rI');
define('NONCE_KEY',        'RjuUf65j*(qpUl^f4XVO%l5-UkIV)?aVqbRqT&Iu7y+pQBcp8ld?zw5KJo-O-u,~');
define('AUTH_SALT',        'yuXy?~Fy!g-D&eBVVtDX*Mhv^Lb@IkUJ(4NC!D.ed!JQ*AVkJydyWY?xt^.YDB,t');
define('SECURE_AUTH_SALT', '+L8uNQtSDrU7I(INkpP6%nhMXr(rPigdVUW,!D765&BW&ni_0VZFe2cBjAiJ1yhT');
define('LOGGED_IN_SALT',   '.shC2l@_w$23Qq?@rQ0g^uU%Q$KKHv0=E)Zuf)~pg(BE2N+=.~O3q=iW8nHl,~Ag');
define('NONCE_SALT',       '2nI2yCrAqdq0#bARnF,GeS.25,XEu1rtNZRf)N?wg&N~yru_7+Dw()$(h$cumxEA');


# Localized Language Stuff

define( 'WP_CACHE', TRUE );

define( 'WP_AUTO_UPDATE_CORE', false );

define( 'PWP_NAME', 'mhc2025dev' );

define( 'FS_METHOD', 'direct' );

define( 'FS_CHMOD_DIR', 0775 );

define( 'FS_CHMOD_FILE', 0664 );

define( 'WPE_APIKEY', '41d933063c057873dda959ae10ae977ce28d8d4f' );

define( 'WPE_CLUSTER_ID', '403805' );

define( 'WPE_CLUSTER_TYPE', 'pod' );

define( 'WPE_ISP', true );

define( 'WPE_BPOD', false );

define( 'WPE_RO_FILESYSTEM', false );

define( 'WPE_LARGEFS_BUCKET', 'largefs.wpengine' );

define( 'WPE_SFTP_PORT', 2222 );

define( 'WPE_SFTP_ENDPOINT', '34.148.30.145' );

define( 'WPE_LBMASTER_IP', '' );

define( 'WPE_CDN_DISABLE_ALLOWED', true );

define( 'DISALLOW_FILE_MODS', FALSE );

define( 'DISALLOW_FILE_EDIT', FALSE );

define( 'DISABLE_WP_CRON', false );

define( 'WPE_FORCE_SSL_LOGIN', false );

define( 'FORCE_SSL_LOGIN', false );

/*SSLSTART*/ if ( isset($_SERVER['HTTP_X_WPE_SSL']) && $_SERVER['HTTP_X_WPE_SSL'] ) $_SERVER['HTTPS'] = 'on'; /*SSLEND*/

define( 'WPE_EXTERNAL_URL', false );

define( 'WP_POST_REVISIONS', FALSE );

define( 'WPE_WHITELABEL', 'wpengine' );

define( 'WP_TURN_OFF_ADMIN_BAR', false );

define( 'WPE_BETA_TESTER', false );

umask(0002);

$wpe_cdn_uris=array ( );

$wpe_no_cdn_uris=array ( );

$wpe_content_regexs=array ( );

$wpe_all_domains=array ( 0 => 'mhc2025dev.wpengine.com', 1 => 'mhc2025dev.wpenginepowered.com', );

$wpe_varnish_servers=array ( 0 => '127.0.0.1', );

$wpe_special_ips=array ( 0 => '104.196.150.43', 1 => 'pod-403805-utility.pod-403805.svc.cluster.local', );

$wpe_netdna_domains=array ( );

$wpe_netdna_domains_secure=array ( );

$wpe_netdna_push_domains=array ( );

$wpe_domain_mappings=array ( );

$memcached_servers=array ( 'default' =>  array ( 0 => 'unix:///tmp/memcached.sock', ), );
define('WPLANG','');

# WP Engine ID


# WP Engine Settings
define('WP_DEBUG', true);
define('WP_DEBUG_LOG', true);
define('WP_DEBUG_DISPLAY', false); // Set to false if you only want logs, not on-screen display
@ini_set('display_errors', 1);


# That's It. Pencils down
if ( !defined('ABSPATH') )
	define('ABSPATH', __DIR__ . '/');
require_once(ABSPATH . 'wp-settings.php');

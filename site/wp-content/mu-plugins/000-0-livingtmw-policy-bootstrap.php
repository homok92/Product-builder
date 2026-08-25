<?php
/** Refresh the established plugin path before WordPress loads the main MU plugin. */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$bootstrap_targets = array(
	__DIR__ . '/000-livingtmw-refresh.php',
	__DIR__ . '/livingtmw-custom.php',
	__DIR__ . '/livingtmw-custom/living-tools-v2.php',
);

if ( function_exists( 'opcache_invalidate' ) ) {
	foreach ( $bootstrap_targets as $bootstrap_target ) {
		if ( is_file( $bootstrap_target ) ) {
			opcache_invalidate( $bootstrap_target, true );
		}
	}
}

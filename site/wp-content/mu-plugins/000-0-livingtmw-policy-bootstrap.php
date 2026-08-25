<?php
/** Load before the host's retained 000 bootstrap and invalidate stale opcodes. */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$bootstrap_targets = array(
	__DIR__ . '/000-livingtmw-refresh.php',
	__DIR__ . '/livingtmw-custom.php',
	__DIR__ . '/livingtmw-custom/living-tools-v2.php',
	__DIR__ . '/livingtmw-custom/living-tools-v3.php',
);

if ( function_exists( 'opcache_invalidate' ) ) {
	foreach ( $bootstrap_targets as $bootstrap_target ) {
		if ( is_file( $bootstrap_target ) ) {
			opcache_invalidate( $bootstrap_target, true );
		}
	}
}

if ( ! function_exists( 'livingtmw_default_market_rates' ) ) {
	require_once __DIR__ . '/livingtmw-custom/living-tools-v3.php';
}

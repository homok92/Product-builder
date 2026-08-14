<?php
/**
 * Plugin Name: LivingTMW Customizations
 * Description: Version-controlled custom code for livingtmw.com.
 * Version: 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action(
	'wp_enqueue_scripts',
	static function (): void {
		$stylesheet = __DIR__ . '/livingtmw-custom/custom.css';

		if ( is_readable( $stylesheet ) ) {
			wp_enqueue_style(
				'livingtmw-custom',
				content_url( 'mu-plugins/livingtmw-custom/custom.css' ),
				array(),
				(string) filemtime( $stylesheet )
			);
		}
	}
);


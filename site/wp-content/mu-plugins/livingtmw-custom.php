<?php
/* Runtime refresh: 2026-08-19 11:00 MMT. */
/**
 * Plugin Name: LivingTMW Customizations
 * Description: Version-controlled custom code for livingtmw.com.
 * Version: 1.1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once __DIR__ . '/livingtmw-custom/adsense-readiness.php';
require_once __DIR__ . '/livingtmw-custom/content-enhancements.php';
require_once __DIR__ . '/livingtmw-custom/content-strategy.php';
if ( ! function_exists( 'livingtmw_default_market_rates' ) ) {
	require_once __DIR__ . '/livingtmw-custom/living-tools.php';
}
require_once __DIR__ . '/livingtmw-custom/daily-fortune.php';

add_action(
	'wp_enqueue_scripts',
	static function (): void {
		$stylesheet = __DIR__ . '/livingtmw-custom/custom-cards-v23.css';
		$site_theme = __DIR__ . '/livingtmw-custom/site-consistency.css';
		$script     = __DIR__ . '/livingtmw-custom/theme-toggle.js';
		$tools      = __DIR__ . '/livingtmw-custom/living-market-v2.js';
		$carousel   = __DIR__ . '/livingtmw-custom/home-carousel.js';
		$fortune    = __DIR__ . '/livingtmw-custom/daily-fortune-v4.js';

		if ( is_readable( $stylesheet ) ) {
			wp_enqueue_style(
				'livingtmw-custom',
				content_url( 'mu-plugins/livingtmw-custom/custom-cards-v23.css' ),
				array(),
				'20260820-fortune-layout'
			);
		}

		if ( is_readable( $site_theme ) ) {
			wp_enqueue_style(
				'livingtmw-site-consistency',
				content_url( 'mu-plugins/livingtmw-custom/site-consistency.css' ),
				array( 'livingtmw-custom' ),
				(string) filemtime( $site_theme )
			);
		}

		if ( is_readable( $script ) ) {
			wp_enqueue_script(
				'livingtmw-theme-toggle',
				content_url( 'mu-plugins/livingtmw-custom/theme-toggle.js' ),
				array(),
				(string) filemtime( $script ),
				true
			);
		}

		if ( is_readable( $tools ) && ! is_admin() ) {
			wp_enqueue_script(
				'livingtmw-living-tools',
				content_url( 'mu-plugins/livingtmw-custom/living-market-v2.js' ),
				array(),
				(string) filemtime( $tools ),
				true
			);
		}

		if ( is_readable( $carousel ) && ( is_front_page() || is_home() ) ) {
			wp_enqueue_script(
				'livingtmw-home-carousel',
				content_url( 'mu-plugins/livingtmw-custom/home-carousel.js' ),
				array(),
				(string) filemtime( $carousel ),
				true
			);
		}

		if ( is_readable( $fortune ) && is_page( 'today-fortune' ) ) {
			wp_enqueue_script(
				'livingtmw-daily-fortune',
				content_url( 'mu-plugins/livingtmw-custom/daily-fortune-v4.js' ),
				array(),
				'20260820-fortune-layout',
				true
			);
		}
	}
);

add_action(
	'wp_head',
	static function (): void {
		?>
		<script id="livingtmw-theme-init">(function(){try{var t=localStorage.getItem('livingtmw-theme');if(t!=='dark'&&t!=='light'){t=matchMedia('(prefers-color-scheme: dark)').matches?'dark':'light'}document.documentElement.dataset.theme=t;document.documentElement.style.colorScheme=t}catch(e){}}());</script>
		<?php
	},
	0
);

add_action(
	'wp_footer',
	static function (): void {
		?>
		<button class="livingtmw-theme-toggle" type="button" aria-label="다크 모드로 전환" aria-pressed="false">
			<span class="livingtmw-theme-toggle__icon" aria-hidden="true"></span>
			<span class="livingtmw-theme-toggle__label">다크 모드</span>
		</button>
		<?php
	}
);

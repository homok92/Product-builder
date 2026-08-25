<?php
/**
 * Final policy guard for hosts that retain stale PHP opcodes after SFTP deploys.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action(
	'template_redirect',
	static function (): void {
		if ( is_admin() || wp_doing_ajax() || is_feed() || is_robots() ) {
			return;
		}

		ob_start(
			static function ( string $html ): string {
				$official_card = '<article class="livingtmw-market-rate livingtmw-market-rate--official"><div class="livingtmw-tool-card__heading"><div><p class="livingtmw-tool-card__kicker">거래 전 직접 확인</p><h3>공식 환율 안내</h3></div><span class="livingtmw-market-rate__icon" aria-hidden="true">💱</span></div><p>환전과 송금 전에는 미얀마 중앙은행 또는 허가받은 금융기관의 당일 고시와 수수료를 직접 확인하세요. 내일의 생활은 비공식 환전 거래를 권유하거나 중개하지 않습니다.</p><p><a href="https://forex.cbm.gov.mm/" target="_blank" rel="noopener noreferrer">미얀마 중앙은행 공식 환율 확인</a></p></article>';

				return (string) preg_replace(
					'~<article class="livingtmw-market-rate[^\"]*"[^>]*>.*?</article>~su',
					$official_card,
					$html
				);
			}
		);
	},
	0
);

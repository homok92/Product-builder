<?php
/**
 * Interactive Yangon weather and living-cost tools for the front page.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function livingtmw_default_market_rates(): array {
	return array(
		'usd_buy'   => 4310,
		'usd_sell'  => 4420,
		'krw_buy'   => 3.05,
		'krw_sell'  => 3.13,
		'updated_at' => '2026-08-15',
	);
}

function livingtmw_sanitize_market_rates( $input ): array {
	$defaults = livingtmw_default_market_rates();
	$input    = is_array( $input ) ? $input : array();

	return array(
		'usd_buy'    => max( 0, (float) ( $input['usd_buy'] ?? $defaults['usd_buy'] ) ),
		'usd_sell'   => max( 0, (float) ( $input['usd_sell'] ?? $defaults['usd_sell'] ) ),
		'krw_buy'    => max( 0, (float) ( $input['krw_buy'] ?? $defaults['krw_buy'] ) ),
		'krw_sell'   => max( 0, (float) ( $input['krw_sell'] ?? $defaults['krw_sell'] ) ),
		'updated_at' => current_time( 'Y-m-d H:i' ),
	);
}

function livingtmw_parse_market_number( string $value ): float {
	return (float) str_replace( ',', '', trim( $value ) );
}

function livingtmw_fetch_live_market_rates( bool $force = false ): array {
	$cache_key = 'livingtmw_egcurrency_rates';
	if ( ! $force ) {
		$cached = get_transient( $cache_key );
		if ( is_array( $cached ) ) {
			return $cached;
		}
	}

	$fallback = get_option( 'livingtmw_market_rates', livingtmw_default_market_rates() );
	$response = wp_remote_get(
		'https://egcurrency.com/en/currency/MMK/blackMarket',
		array(
			'timeout'    => 12,
			'redirection' => 3,
			'user-agent' => 'LivingTMW/1.0 (+https://livingtmw.com/; exchange-rate widget)',
		)
	);

	if ( is_wp_error( $response ) || 200 !== wp_remote_retrieve_response_code( $response ) ) {
		$fallback['status'] = 'stale';
		return $fallback;
	}

	$html = wp_remote_retrieve_body( $response );
	$text = preg_replace( '/\s+/u', ' ', wp_strip_all_tags( $html ) );
	$usd  = array();
	$krw  = array();

	preg_match( '/US Dollar\s*\(USD\)\s*([0-9,.]+)\s*([0-9,.]+)/iu', $text, $usd );
	preg_match( '/1000\s*South Korean Won\s*\(KRW\)\s*([0-9,.]+)\s*([0-9,.]+)/iu', $text, $krw );

	if ( empty( $usd[1] ) || empty( $usd[2] ) || empty( $krw[1] ) || empty( $krw[2] ) ) {
		$fallback['status'] = 'stale';
		return $fallback;
	}

	$rates = array(
		'usd_buy'    => livingtmw_parse_market_number( $usd[1] ),
		'usd_sell'   => livingtmw_parse_market_number( $usd[2] ),
		'krw_buy'    => livingtmw_parse_market_number( $krw[1] ) / 1000,
		'krw_sell'   => livingtmw_parse_market_number( $krw[2] ) / 1000,
		'updated_at' => current_time( 'Y-m-d H:i' ),
		'status'     => 'live',
	);

	if ( $rates['usd_buy'] < 1000 || $rates['usd_buy'] > 10000 || $rates['krw_buy'] < 0.5 || $rates['krw_buy'] > 10 ) {
		$fallback['status'] = 'stale';
		return $fallback;
	}

	if ( preg_match( '/Live exchange rate for Myanmar Kyat at Black Market,\s*([^<]+)/iu', $html, $source_time ) ) {
		$rates['source_updated_at'] = sanitize_text_field( trim( wp_strip_all_tags( $source_time[1] ) ) );
	}

	update_option( 'livingtmw_market_rates', $rates, false );
	set_transient( $cache_key, $rates, 15 * MINUTE_IN_SECONDS );
	return $rates;
}

add_action(
	'rest_api_init',
	static function (): void {
		register_rest_route(
			'livingtmw/v1',
			'/market-rate',
			array(
				'methods'             => WP_REST_Server::READABLE,
				'permission_callback' => '__return_true',
				'callback'            => static function (): WP_REST_Response {
					$response = new WP_REST_Response( livingtmw_fetch_live_market_rates() );
					$response->header( 'Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0' );
					return $response;
				},
			)
		);
	}
);

add_action(
	'admin_init',
	static function (): void {
		register_setting(
			'livingtmw_market_rates_group',
			'livingtmw_market_rates',
			array(
				'type'              => 'array',
				'sanitize_callback' => 'livingtmw_sanitize_market_rates',
				'default'           => livingtmw_default_market_rates(),
			)
		);
	}
);

add_action(
	'admin_menu',
	static function (): void {
		add_options_page( '현지 환율', '현지 환율', 'manage_options', 'livingtmw-market-rates', 'livingtmw_render_market_rates_page' );
	}
);

function livingtmw_render_market_rates_page(): void {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}
	$rates = get_option( 'livingtmw_market_rates', livingtmw_default_market_rates() );
	?>
	<div class="wrap">
		<h1>현지 바깥환율</h1>
		<p>Myanmar Market Price 앱에 표시된 BUY·SELL 값을 입력하세요. 저장 시 홈페이지의 업데이트 시각도 함께 갱신됩니다.</p>
		<form method="post" action="options.php">
			<?php settings_fields( 'livingtmw_market_rates_group' ); ?>
			<table class="form-table" role="presentation"><tbody>
				<tr><th scope="row">USD BUY</th><td><input type="number" min="0" step="1" name="livingtmw_market_rates[usd_buy]" value="<?php echo esc_attr( $rates['usd_buy'] ); ?>"> MMK</td></tr>
				<tr><th scope="row">USD SELL</th><td><input type="number" min="0" step="1" name="livingtmw_market_rates[usd_sell]" value="<?php echo esc_attr( $rates['usd_sell'] ); ?>"> MMK</td></tr>
				<tr><th scope="row">KRW BUY</th><td><input type="number" min="0" step="0.01" name="livingtmw_market_rates[krw_buy]" value="<?php echo esc_attr( $rates['krw_buy'] ); ?>"> MMK</td></tr>
				<tr><th scope="row">KRW SELL</th><td><input type="number" min="0" step="0.01" name="livingtmw_market_rates[krw_sell]" value="<?php echo esc_attr( $rates['krw_sell'] ); ?>"> MMK</td></tr>
			</tbody></table>
			<?php submit_button( '환율 업데이트' ); ?>
		</form>
	</div>
	<?php
}

function livingtmw_render_living_tools(): void {
	if ( ! is_front_page() && ! is_home() ) {
		return;
	}
	$rates = get_option( 'livingtmw_market_rates', livingtmw_default_market_rates() );
	$fortune_page = get_page_by_path( 'today-fortune', OBJECT, 'page' );
	$fortune_url  = $fortune_page ? get_permalink( $fortune_page ) : home_url( '/today-fortune/' );
	$guide_page   = get_page_by_path( 'myanmar-life-guide', OBJECT, 'page' );
	$guide_url    = $guide_page ? get_permalink( $guide_page ) : home_url( '/myanmar-life-guide/' );
	$latest_posts = get_posts(
		array(
			'post_type'      => 'post',
			'post_status'    => 'publish',
			'posts_per_page' => 5,
			'orderby'        => 'date',
			'order'          => 'DESC',
		)
	);
	?>
	<section class="livingtmw-tools" aria-labelledby="livingtmw-tools-title">
		<div class="livingtmw-home-notice" role="note">
			<span>현지 정보 안내</span>
			<a href="<?php echo esc_url( $guide_url ); ?>">미얀마 정착 전 꼭 확인할 생활 준비 순서</a>
			<small>가격·정책은 변동될 수 있어 확인일과 출처를 함께 안내합니다.</small>
		</div>

		<nav class="livingtmw-home-nav" aria-label="홈페이지 주요 메뉴">
			<a aria-current="page" href="<?php echo esc_url( home_url( '/' ) ); ?>">홈</a>
			<a href="<?php echo esc_url( $guide_url ); ?>">처음 시작하기</a>
			<a href="<?php echo esc_url( home_url( '/category/' . LIVINGTMW_PRIMARY_CATEGORY_SLUG . '/' ) ); ?>">생활 가이드</a>
			<a href="<?php echo esc_url( home_url( '/about-livingtmw/' ) ); ?>">사이트 소개</a>
			<a href="<?php echo esc_url( home_url( '/editorial-policy/' ) ); ?>">검수 원칙</a>
		</nav>

		<div class="livingtmw-home-hero">
			<section class="livingtmw-content-picker" aria-labelledby="livingtmw-content-picker-title">
				<div class="livingtmw-content-picker__copy">
					<p class="livingtmw-tools__eyebrow">MYANMAR LIFE · START HERE</p>
					<h1 id="livingtmw-content-picker-title">한국인의<br>미얀마 생활 안내</h1>
					<p class="livingtmw-content-picker__summary">주거부터 환전, 통신, 교통까지 양곤에서 직접 부딪히며 확인한 정보를 한곳에 모았습니다.</p>
					<div class="livingtmw-content-picker__actions">
						<a class="livingtmw-content-picker__button livingtmw-content-picker__button--fortune" href="<?php echo esc_url( $guide_url ); ?>">생활 준비 시작하기 <span aria-hidden="true">→</span></a>
						<a class="livingtmw-content-picker__button livingtmw-content-picker__button--tips" href="<?php echo esc_url( $fortune_url ); ?>">오늘의 운세</a>
					</div>
				</div>
				<figure class="livingtmw-content-picker__visual">
					<img src="<?php echo esc_url( content_url( 'mu-plugins/livingtmw-custom/images/articles/yangon-arrival-essentials.png' ) ); ?>" alt="양곤 정착 생활 준비 안내 이미지" width="1200" height="800">
					<figcaption><span>FEATURED GUIDE</span> 양곤 정착 첫 주에 필요한 준비</figcaption>
				</figure>
			</section>

			<aside class="livingtmw-home-brief" aria-labelledby="livingtmw-home-brief-title">
				<header><h2 id="livingtmw-home-brief-title">정착 준비 순서</h2><span>CHECKLIST</span></header>
				<ol>
					<li><b>01</b><a href="<?php echo esc_url( livingtmw_guide_post_url( 8 ) ); ?>"><strong>월 생활비 범위 정하기</strong><small>식비·월세·교통비 분리</small></a></li>
					<li><b>02</b><a href="<?php echo esc_url( livingtmw_guide_post_url( 11 ) ); ?>"><strong>집과 전력 조건 확인</strong><small>발전기·수질·계약 점검</small></a></li>
					<li><b>03</b><a href="<?php echo esc_url( livingtmw_guide_post_url( 17 ) ); ?>"><strong>SIM과 인터넷 연결</strong><small>도착 당일 통신 준비</small></a></li>
					<li><b>04</b><a href="<?php echo esc_url( livingtmw_guide_post_url( 21 ) ); ?>"><strong>환전과 결제 준비</strong><small>은행·KBZPay 사용 확인</small></a></li>
					<li><b>05</b><a href="<?php echo esc_url( livingtmw_guide_post_url( 25 ) ); ?>"><strong>이동 동선 시험하기</strong><small>Grab·우기 소요시간 확인</small></a></li>
				</ol>
			</aside>
		</div>

		<div class="livingtmw-tools__intro">
			<p class="livingtmw-tools__eyebrow">오늘의 양곤 생활 도구</p>
			<h2 id="livingtmw-tools-title">날씨와 환율을 한눈에 확인하세요</h2>
			<p>양곤 생활에 필요한 실시간 예보와 환율 정보를 한곳에 모았습니다.</p>
		</div>

		<div class="livingtmw-tools__grid">
			<article class="livingtmw-weather" aria-labelledby="livingtmw-weather-title" data-latitude="16.8409" data-longitude="96.1735">
				<div class="livingtmw-tool-card__heading">
					<div>
						<p class="livingtmw-tool-card__kicker">실시간 업데이트</p>
						<h3 id="livingtmw-weather-title">오늘의 양곤 생활 난이도</h3>
					</div>
					<span class="livingtmw-weather__icon" aria-hidden="true">⛅</span>
				</div>
				<div class="livingtmw-weather__loading" role="status">양곤 날씨와 대기질을 불러오는 중입니다…</div>
				<div class="livingtmw-weather__content" hidden>
					<div class="livingtmw-weather__score-row">
						<strong class="livingtmw-weather__score"><span data-weather-score>--</span><small>/100</small></strong>
						<div><span class="livingtmw-weather__level" data-weather-level>확인 중</span><p data-weather-summary></p></div>
					</div>
					<dl class="livingtmw-weather__metrics">
						<div><dt>현재</dt><dd data-weather-temperature>--</dd></div>
						<div><dt>체감</dt><dd data-weather-apparent>--</dd></div>
						<div><dt>6시간 비</dt><dd data-weather-rain>--</dd></div>
						<div><dt>대기질</dt><dd data-weather-aqi>--</dd></div>
					</dl>
					<ul class="livingtmw-weather__tips" data-weather-tips></ul>
					<p class="livingtmw-tool-card__meta">양곤 중심부 기준 · 예보 모델은 실제 골목별 침수를 측정하지 않습니다. <a href="https://open-meteo.com/" target="_blank" rel="noopener noreferrer">Open-Meteo</a></p>
				</div>
				<div class="livingtmw-weather__error" hidden>
					<p>현재 날씨를 불러오지 못했습니다. 잠시 뒤 다시 시도해 주세요.</p>
					<button type="button" data-weather-retry>다시 불러오기</button>
				</div>
			</article>

			<article class="livingtmw-market-rate" aria-labelledby="livingtmw-market-title">
				<div class="livingtmw-tool-card__heading">
					<div>
						<p class="livingtmw-tool-card__kicker">15분마다 자동 확인</p>
						<h3 id="livingtmw-market-title">오늘의 바깥환율</h3>
					</div>
					<span class="livingtmw-market-rate__icon" aria-hidden="true">💱</span>
				</div>
				<div class="livingtmw-market-rate__rows">
					<div><strong>1 USD</strong><span><small>BUY</small><b data-market-usd-buy><?php echo esc_html( number_format( (float) $rates['usd_buy'], 2, '.', ',' ) ); ?></b><em>MMK</em></span><span><small>SELL</small><b data-market-usd-sell><?php echo esc_html( number_format( (float) $rates['usd_sell'], 2, '.', ',' ) ); ?></b><em>MMK</em></span></div>
					<div><strong>1원</strong><span><small>BUY</small><b data-market-krw-buy><?php echo esc_html( number_format( (float) $rates['krw_buy'], 2, '.', ',' ) ); ?></b><em>MMK</em></span><span><small>SELL</small><b data-market-krw-sell><?php echo esc_html( number_format( (float) $rates['krw_sell'], 2, '.', ',' ) ); ?></b><em>MMK</em></span></div>
				</div>
				<p><span data-market-status>최신값 확인 중</span> · EGCurrency Black Market 기준 비공식 시장 참고값이며 실제 거래가는 달라질 수 있습니다. <a href="https://egcurrency.com/en/currency/MMK/blackMarket" target="_blank" rel="noopener noreferrer">출처</a></p>
			</article>
		</div>

		<div class="livingtmw-home-dashboard">
			<section class="livingtmw-home-panel livingtmw-home-topics" aria-labelledby="livingtmw-home-topics-title">
				<header><div><p class="livingtmw-tools__eyebrow">주제별 생활 정보</p><h2 id="livingtmw-home-topics-title">필요한 정보부터 찾아보세요</h2></div><a href="<?php echo esc_url( $guide_url ); ?>">전체 안내 보기 →</a></header>
				<div class="livingtmw-home-topics__grid">
					<a href="<?php echo esc_url( livingtmw_guide_post_url( 11 ) ); ?>"><span>01</span><strong>주거·계약</strong><small>월세, 전력, 수질 확인</small></a>
					<a href="<?php echo esc_url( livingtmw_guide_post_url( 8 ) ); ?>"><span>02</span><strong>생활비</strong><small>예산과 실제 지출</small></a>
					<a href="<?php echo esc_url( livingtmw_guide_post_url( 17 ) ); ?>"><span>03</span><strong>통신</strong><small>SIM과 가정 인터넷</small></a>
					<a href="<?php echo esc_url( livingtmw_guide_post_url( 21 ) ); ?>"><span>04</span><strong>금융·환전</strong><small>계좌, 결제, 환율</small></a>
					<a href="<?php echo esc_url( livingtmw_guide_post_url( 25 ) ); ?>"><span>05</span><strong>교통</strong><small>Grab과 이동 안전</small></a>
					<a href="<?php echo esc_url( livingtmw_guide_post_url( 27 ) ); ?>"><span>06</span><strong>장보기</strong><small>마트와 식료품 구매</small></a>
				</div>
			</section>

			<aside class="livingtmw-home-panel livingtmw-home-trust" aria-labelledby="livingtmw-home-trust-title">
				<p class="livingtmw-tools__eyebrow">내일의 생활 기준</p>
				<h2 id="livingtmw-home-trust-title">직접 써보고, 다시 확인합니다</h2>
				<ul><li>양곤 현지 생활 경험 구분</li><li>변동 정보의 확인 시점 표시</li><li>공식 자료와 참고 출처 연결</li><li>광고와 편집 콘텐츠 분리</li></ul>
				<a href="<?php echo esc_url( home_url( '/editorial-policy/' ) ); ?>">콘텐츠 제작·검수 원칙 →</a>
			</aside>
		</div>

		<?php if ( $latest_posts ) : ?>
		<section class="livingtmw-home-panel livingtmw-home-latest" aria-labelledby="livingtmw-home-latest-title">
			<header><div><p class="livingtmw-tools__eyebrow">최근 업데이트</p><h2 id="livingtmw-home-latest-title">최신 양곤 생활 가이드</h2></div><a href="<?php echo esc_url( home_url( '/category/' . LIVINGTMW_PRIMARY_CATEGORY_SLUG . '/' ) ); ?>">전체 글 보기 →</a></header>
			<ol>
				<?php foreach ( $latest_posts as $index => $latest_post ) : ?>
				<li><span><?php echo esc_html( str_pad( (string) ( $index + 1 ), 2, '0', STR_PAD_LEFT ) ); ?></span><a href="<?php echo esc_url( get_permalink( $latest_post ) ); ?>"><?php echo esc_html( get_the_title( $latest_post ) ); ?></a><time datetime="<?php echo esc_attr( get_the_date( 'c', $latest_post ) ); ?>"><?php echo esc_html( get_the_date( 'Y.m.d', $latest_post ) ); ?></time></li>
				<?php endforeach; ?>
			</ol>
		</section>
		<?php endif; ?>
	</section>
	<?php
}

add_action( 'generate_before_main_content', 'livingtmw_render_living_tools', 5 );

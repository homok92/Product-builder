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
	$recent_posts = get_posts(
		array(
			'post_type'      => 'post',
			'post_status'    => 'publish',
			'posts_per_page' => 6,
			'orderby'        => 'date',
			'order'          => 'DESC',
		)
	);
	$featured     = $recent_posts[0] ?? null;
	$featured_url = $featured instanceof WP_Post ? get_permalink( $featured ) : $guide_url;
	$featured_img = $featured instanceof WP_Post ? livingtmw_seo_image( (int) $featured->ID ) : array();
	$featured_src = $featured_img['url'] ?? content_url( 'mu-plugins/livingtmw-custom/images/articles/yangon-arrival-essentials.png' );
	?>
	<section class="livingtmw-tools" aria-labelledby="livingtmw-tools-title">
		<nav class="livingtmw-home-menu" aria-label="홈페이지 주요 메뉴">
			<div class="livingtmw-home-brand"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" aria-label="내일의 생활 홈"><img src="<?php echo esc_url( content_url( 'uploads/2026/08/cropped-logo3.png' ) ); ?>" alt="내일의 생활" width="1342" height="434"></a></div>
			<div class="livingtmw-home-menu__links">
				<a class="is-current" href="<?php echo esc_url( home_url( '/' ) ); ?>">홈</a>
				<a href="<?php echo esc_url( home_url( '/about-livingtmw/' ) ); ?>">사이트 소개</a>
				<a href="<?php echo esc_url( $guide_url ); ?>">미얀마 생활</a>
				<a href="<?php echo esc_url( home_url( '/category/' . LIVINGTMW_PRIMARY_CATEGORY_SLUG . '/' ) ); ?>">미얀마 가이드</a>
				<a href="<?php echo esc_url( $fortune_url ); ?>">오늘의 운세</a>
				<details class="livingtmw-home-search">
					<summary aria-label="검색 열기"><svg aria-hidden="true" viewBox="0 0 24 24" width="20" height="20"><circle cx="11" cy="11" r="6.5"></circle><path d="m16 16 5 5"></path></svg><span class="screen-reader-text">검색</span></summary>
					<form role="search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>">
						<label><span class="screen-reader-text">검색어</span><input type="search" name="s" placeholder="검색어를 입력하세요" required></label>
						<button type="submit">검색</button>
					</form>
				</details>
			</div>
		</nav>

		<article class="livingtmw-home-feature" aria-labelledby="livingtmw-content-picker-title">
				<img src="<?php echo esc_url( $featured_src ); ?>" alt="" width="1200" height="800">
				<div class="livingtmw-home-feature__shade"></div>
				<div class="livingtmw-home-feature__content">
					<p class="livingtmw-tools__eyebrow">FEATURED · MYANMAR LIFE</p>
					<h1 id="livingtmw-content-picker-title">한국인을 위한<br>미얀마·양곤 생활 정보</h1>
					<p>주거, 생활비, 통신, 금융과 교통 정보를 실제 양곤 생활 경험과 확인 가능한 자료로 정리합니다.</p>
					<a href="<?php echo esc_url( $featured_url ); ?>"><?php echo $featured instanceof WP_Post ? esc_html( get_the_title( $featured ) ) : '생활 준비 가이드 읽기'; ?> <span aria-hidden="true">→</span></a>
				</div>
		</article>

		<div class="livingtmw-home-layout">
			<div class="livingtmw-home-stream">

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
					<div class="livingtmw-weather__score-row"><strong class="livingtmw-weather__score"><span data-weather-score>--</span><small>/100</small></strong><div><span class="livingtmw-weather__level" data-weather-level>확인 중</span><p data-weather-summary></p></div></div>
					<dl class="livingtmw-weather__metrics"><div><dt>현재</dt><dd data-weather-temperature>--</dd></div><div><dt>체감</dt><dd data-weather-apparent>--</dd></div><div><dt>비 올 확률<br>(6시간 이내)</dt><dd data-weather-rain>--</dd></div><div><dt>대기질</dt><dd data-weather-aqi>--</dd></div></dl>
					<ul class="livingtmw-weather__tips" data-weather-tips></ul>
					<p class="livingtmw-tool-card__meta">양곤 중심부 기준 · 예보 모델은 실제 골목별 침수를 측정하지 않습니다. <a href="https://open-meteo.com/" target="_blank" rel="noopener noreferrer">Open-Meteo</a></p>
				</div>
				<div class="livingtmw-weather__error" hidden><p>현재 날씨를 불러오지 못했습니다. 잠시 뒤 다시 시도해 주세요.</p><button type="button" data-weather-retry>다시 불러오기</button></div>
			</article>

			<article class="livingtmw-market-rate" aria-labelledby="livingtmw-market-title">
				<div class="livingtmw-tool-card__heading"><div><p class="livingtmw-tool-card__kicker">15분마다 자동 확인</p><h3 id="livingtmw-market-title">오늘의 바깥환율</h3></div><span class="livingtmw-market-rate__icon" aria-hidden="true">💱</span></div>
				<div class="livingtmw-market-rate__rows">
					<div><strong>1 USD</strong><span><small>BUY</small><b data-market-usd-buy><?php echo esc_html( number_format( (float) $rates['usd_buy'], 2, '.', ',' ) ); ?></b><em>MMK</em></span><span><small>SELL</small><b data-market-usd-sell><?php echo esc_html( number_format( (float) $rates['usd_sell'], 2, '.', ',' ) ); ?></b><em>MMK</em></span></div>
					<div><strong>1원</strong><span><small>BUY</small><b data-market-krw-buy><?php echo esc_html( number_format( (float) $rates['krw_buy'], 2, '.', ',' ) ); ?></b><em>MMK</em></span><span><small>SELL</small><b data-market-krw-sell><?php echo esc_html( number_format( (float) $rates['krw_sell'], 2, '.', ',' ) ); ?></b><em>MMK</em></span></div>
				</div>
				<p><span data-market-status>최신값 확인 중</span> · EGCurrency Black Market 기준 비공식 시장 참고값이며 실제 거래가는 달라질 수 있습니다. <a href="https://egcurrency.com/en/currency/MMK/blackMarket" target="_blank" rel="noopener noreferrer">출처</a></p>
			</article>
		</div>
			</div>

			<aside class="livingtmw-home-rail" aria-label="홈페이지 보조 정보">
			<section class="livingtmw-home-steps" aria-labelledby="livingtmw-home-steps-title">
				<header><h2 id="livingtmw-home-steps-title">정착 준비 순서</h2><span>START HERE</span></header>
				<ol>
					<li><b>01</b><a href="<?php echo esc_url( livingtmw_guide_post_url( 8 ) ); ?>"><strong>예산 범위 정하기</strong><small>생활비와 비상금 계산</small></a></li>
					<li><b>02</b><a href="<?php echo esc_url( livingtmw_guide_post_url( 11 ) ); ?>"><strong>주거 조건 확인</strong><small>전력·수질·계약 점검</small></a></li>
					<li><b>03</b><a href="<?php echo esc_url( livingtmw_guide_post_url( 17 ) ); ?>"><strong>통신 연결</strong><small>SIM·인터넷 개통</small></a></li>
					<li><b>04</b><a href="<?php echo esc_url( livingtmw_guide_post_url( 21 ) ); ?>"><strong>금융 준비</strong><small>환전·계좌·결제</small></a></li>
					<li><b>05</b><a href="<?php echo esc_url( livingtmw_guide_post_url( 25 ) ); ?>"><strong>생활 동선 만들기</strong><small>교통·마트·병원</small></a></li>
				</ol>
			</section>
		<?php if ( $recent_posts ) : ?>
		<section class="livingtmw-home-news" aria-labelledby="livingtmw-home-news-title">
			<header><div><p class="livingtmw-tools__eyebrow">LATEST ARTICLES</p><h2 id="livingtmw-home-news-title">최신 양곤 생활 가이드</h2></div><a href="<?php echo esc_url( home_url( '/category/' . LIVINGTMW_PRIMARY_CATEGORY_SLUG . '/' ) ); ?>">전체 글 보기 →</a></header>
			<ol>
				<?php foreach ( $recent_posts as $index => $recent_post ) : ?>
				<li><b><?php echo esc_html( (string) ( $index + 1 ) ); ?></b><a href="<?php echo esc_url( get_permalink( $recent_post ) ); ?>"><?php echo esc_html( get_the_title( $recent_post ) ); ?></a><time datetime="<?php echo esc_attr( get_the_date( 'c', $recent_post ) ); ?>"><?php echo esc_html( get_the_date( 'Y.m.d', $recent_post ) ); ?></time></li>
				<?php endforeach; ?>
			</ol>
		</section>
		<?php endif; ?>
			</aside>
		</div>
	</section>
	<?php
}

add_action( 'generate_before_main_content', 'livingtmw_render_living_tools', 5 );

/** Use the custom full-width dashboard instead of the theme sidebar on home. */
add_filter(
	'generate_sidebar_layout',
	static function ( string $layout ): string {
		return ( is_front_page() || is_home() ) ? 'no-sidebar' : $layout;
	}
);

/** Keep the primary navigation concise and consistent on every screen. */
add_filter(
	'wp_nav_menu_items',
	static function ( string $items, $args ): string {
		if ( is_admin() || empty( $args->theme_location ) || 'primary' !== $args->theme_location ) {
			return $items;
		}

		$links = array(
			'홈'         => home_url( '/' ),
			'사이트 소개'   => home_url( '/about-livingtmw/' ),
			'미얀마 생활'   => home_url( '/myanmar-life-guide/' ),
			'미얀마 가이드' => home_url( '/category/' . LIVINGTMW_PRIMARY_CATEGORY_SLUG . '/' ),
			'오늘의 운세' => home_url( '/today-fortune/' ),
		);
		$html = '';
		foreach ( $links as $label => $url ) {
			$html .= '<li class="menu-item"><a href="' . esc_url( $url ) . '">' . esc_html( $label ) . '</a></li>';
		}
		return $html;
	},
	30,
	2
);

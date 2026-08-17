<?php
/**
 * Trust, transparency, and crawl-quality improvements for livingtmw.com.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Create durable trust pages once. Existing pages are never overwritten.
 */
function livingtmw_ensure_trust_pages(): void {
	$pages = array(
		'about-livingtmw' => array(
			'title'   => '사이트 소개',
			'content' => '<h2>내일의 생활은 어떤 사이트인가요?</h2>
<p><strong>내일의 생활</strong>은 미얀마와 양곤에서 생활하거나 체류를 준비하는 한국어 이용자에게 실용적인 생활 정보를 제공하는 정보형 웹사이트입니다. 주거, 생활비, 통신, 금융, 교통, 쇼핑처럼 현지 생활에서 자주 마주치는 주제를 이해하기 쉬운 한국어로 정리합니다.</p>
<h2>콘텐츠의 목적</h2>
<p>각 글은 독자가 필요한 정보를 빠르게 찾고 실제 선택에 참고할 수 있도록 만드는 것을 목표로 합니다. 단순한 검색 유입보다 질문에 충분히 답하는 콘텐츠, 출처와 확인 시점을 알 수 있는 콘텐츠, 변경된 상황을 반영하는 콘텐츠를 지향합니다.</p>
<h2>정보 이용 시 참고사항</h2>
<p>미얀마의 환율, 물가, 법률, 행정 절차와 사업자별 서비스 조건은 예고 없이 달라질 수 있습니다. 중요한 계약이나 금융·법률·의료 결정을 내리기 전에는 관계 기관과 해당 서비스 제공자의 최신 공식 안내를 함께 확인해 주세요.</p>
<h2>운영 및 문의</h2>
<p>사이트는 내일의 생활 편집팀이 운영합니다. 잘못된 정보, 변경된 정보, 저작권 문제 또는 정정이 필요한 내용을 발견하면 <a href="mailto:help@livingtmw.com">help@livingtmw.com</a>으로 알려주세요. 확인 가능한 내용을 검토해 필요한 경우 수정하겠습니다.</p>',
		),
		'editorial-policy' => array(
			'title'   => '콘텐츠 제작 및 검수 원칙',
			'content' => '<h2>독자를 위한 콘텐츠</h2>
<p>내일의 생활은 검색엔진만을 위한 글이 아니라 미얀마 생활 정보를 찾는 독자에게 직접 도움이 되는 글을 제작합니다. 제목과 본문은 실제 질문을 명확하게 설명하고, 불필요한 반복이나 과장된 표현을 피합니다.</p>
<h2>자료 확인과 출처</h2>
<p>정책, 요금, 환율, 제도처럼 변동 가능성이 큰 정보는 가능한 경우 관계 기관, 사업자 또는 신뢰할 수 있는 공개 자료를 확인합니다. 외부 자료를 참고할 때에는 내용을 그대로 복제하지 않고 독자가 이해하기 쉬운 설명과 맥락을 더합니다.</p>
<h2>최신성 및 수정</h2>
<p>게시물에는 작성일을 표시하며 내용이 실질적으로 바뀐 경우 수정일을 함께 표시합니다. 단순히 최신 글처럼 보이기 위한 날짜 변경은 하지 않습니다. 현지 상황이 달라졌거나 오류가 확인되면 근거를 검토한 뒤 내용을 수정합니다.</p>
<h2>광고와 편집의 분리</h2>
<p>광고나 제휴 관계가 콘텐츠의 결론을 좌우하지 않도록 노력합니다. 광고 또는 제휴 링크가 포함된 콘텐츠는 독자가 알아볼 수 있도록 표시하며, 광고를 콘텐츠나 탐색 메뉴로 오인하게 만드는 배치를 사용하지 않습니다.</p>
<h2>정정 요청</h2>
<p>사실 오류나 최신 정보 제보는 <a href="mailto:help@livingtmw.com">help@livingtmw.com</a>으로 보내주세요. 관련 글 주소와 확인 가능한 근거를 함께 보내주시면 검토에 도움이 됩니다.</p>',
		),
		'advertising-disclosure' => array(
			'title'   => '광고 및 이미지 공개 원칙',
			'content' => '<h2>광고 운영 원칙</h2>
<p>내일의 생활은 사이트 운영비를 충당하기 위해 Google AdSense를 포함한 제3자 광고를 표시할 수 있습니다. 광고가 게재되더라도 광고주가 일반 게시물의 주제 선정이나 결론을 결정하지 않으며, 광고를 메뉴·다운로드 버튼·본문 링크처럼 오인하게 만드는 표현을 사용하지 않습니다.</p>
<h2>광고와 제휴 표시</h2>
<p>대가를 받거나 제휴 수익이 발생할 수 있는 콘텐츠는 해당 글에서 독자가 알아볼 수 있게 별도로 알립니다. 별도 표시가 없는 생활정보 글은 특정 업체의 의뢰로 작성된 광고성 게시물이 아닙니다.</p>
<h2>설명용 이미지</h2>
<p>게시물의 일부 대표 이미지는 주제를 쉽게 이해하도록 AI로 제작한 설명용 이미지입니다. 실제 장소, 인물, 상품 또는 진료 장면을 촬영한 사진이 아니며, 해당 이미지의 캡션에도 이 사실을 표시합니다. 정보 판단은 이미지가 아니라 본문과 연결된 자료를 기준으로 해주세요.</p>
<h2>광고 관련 문의</h2>
<p>광고가 콘텐츠나 사이트 기능으로 오인될 수 있거나 부적절한 광고를 발견한 경우 화면과 페이지 주소를 <a href="mailto:help@livingtmw.com">help@livingtmw.com</a>으로 보내주세요.</p>',
		),
	);

	foreach ( $pages as $slug => $page ) {
		if ( get_page_by_path( $slug, OBJECT, 'page' ) ) {
			continue;
		}

		wp_insert_post(
			array(
				'post_title'     => $page['title'],
				'post_name'      => $slug,
				'post_content'   => $page['content'],
				'post_status'    => 'publish',
				'post_type'      => 'page',
				'comment_status' => 'closed',
			)
		);
	}
}
add_action( 'init', 'livingtmw_ensure_trust_pages', 20 );

/**
 * Publish a substantive start-here guide that helps readers navigate the
 * site's first-hand Myanmar living articles in a useful decision order.
 */
function livingtmw_ensure_life_guide_page(): void {
	$page    = get_page_by_path( 'myanmar-life-guide', OBJECT, 'page' );
	$version = '1';
	$content = '[livingtmw_life_guide]';

	if ( $page ) {
		if ( $version !== get_option( 'livingtmw_life_guide_version' ) ) {
			wp_update_post(
				array(
					'ID'           => (int) $page->ID,
					'post_title'   => '한국인의 미얀마 생활 시작 안내',
					'post_content' => $content,
				)
			);
			update_option( 'livingtmw_life_guide_version', $version, false );
		}
		return;
	}

	$post_id = wp_insert_post(
		array(
			'post_title'     => '한국인의 미얀마 생활 시작 안내',
			'post_name'      => 'myanmar-life-guide',
			'post_content'   => $content,
			'post_status'    => 'publish',
			'post_type'      => 'page',
			'comment_status' => 'closed',
		)
	);
	if ( $post_id && ! is_wp_error( $post_id ) ) {
		update_option( 'livingtmw_life_guide_version', $version, false );
	}
}
add_action( 'init', 'livingtmw_ensure_life_guide_page', 22 );

function livingtmw_guide_post_url( int $post_id ): string {
	$url = get_permalink( $post_id );
	return $url ? $url : home_url( '/' );
}

function livingtmw_render_life_guide(): string {
	$links = array(
		'housing'     => livingtmw_guide_post_url( 11 ),
		'rent'        => livingtmw_guide_post_url( 13 ),
		'electricity' => livingtmw_guide_post_url( 15 ),
		'sim'         => livingtmw_guide_post_url( 17 ),
		'internet'    => livingtmw_guide_post_url( 19 ),
		'bank'        => livingtmw_guide_post_url( 21 ),
		'exchange'    => livingtmw_guide_post_url( 23 ),
		'taxi'        => livingtmw_guide_post_url( 25 ),
		'grocery'     => livingtmw_guide_post_url( 27 ),
		'shopping'    => livingtmw_guide_post_url( 29 ),
		'korean_food' => livingtmw_guide_post_url( 31 ),
		'driving'     => livingtmw_guide_post_url( 33 ),
		'car_cost'    => livingtmw_guide_post_url( 35 ),
		'budget'      => livingtmw_guide_post_url( 8 ),
	);

	ob_start();
	?>
	<article class="livingtmw-life-guide">
		<header class="livingtmw-life-guide__intro">
			<p class="livingtmw-tools__eyebrow">처음 방문자를 위한 읽기 순서</p>
			<h2>미얀마 생활 준비, 무엇부터 확인해야 할까요?</h2>
			<p>미얀마 생활은 월세 하나만 비교해서 준비하기 어렵습니다. 전력과 수질, 이동 거리, 환율, 통신 개통과 현금 사용 환경이 서로 연결되어 실제 생활비와 편의성을 결정합니다. 이 안내서는 내일의 생활에 공개된 현지 경험과 확인 자료를 주제별로 묶어, 출국 전부터 입주 후까지 필요한 순서대로 살펴볼 수 있게 정리했습니다.</p>
		</header>

		<nav class="livingtmw-life-guide__steps" aria-label="미얀마 생활 준비 순서">
			<a href="#guide-budget"><span>1</span><strong>예산</strong><small>월 지출 범위 잡기</small></a>
			<a href="#guide-home"><span>2</span><strong>주거</strong><small>전력·수질 확인</small></a>
			<a href="#guide-money"><span>3</span><strong>금융</strong><small>환전·계좌 준비</small></a>
			<a href="#guide-connect"><span>4</span><strong>통신</strong><small>SIM·인터넷 개통</small></a>
			<a href="#guide-move"><span>5</span><strong>이동·장보기</strong><small>생활 동선 만들기</small></a>
		</nav>

		<section id="guide-budget" class="livingtmw-life-guide__section">
			<div><span>STEP 1</span><h2>먼저 월 예산의 범위를 잡습니다</h2></div>
			<p>양곤에서는 현지식과 대중교통만 이용할 때와 외국인 선호 아파트, 에어컨, 수입 식품, Grab을 자주 이용할 때의 비용 차이가 큽니다. 월세를 제외한 생활비만 보거나 공식 환율만 적용하면 실제 필요한 현금을 과소평가할 수 있습니다. 식비·주거·전기·교통을 따로 기록하고 최소 예산과 여유 예산을 함께 준비하세요.</p>
			<ul><li><a href="<?php echo esc_url( $links['budget'] ); ?>">2026년 양곤 한 달 생활비 실제 기준</a></li><li><a href="<?php echo esc_url( $links['electricity'] ); ?>">미얀마 전기세와 냉방 비용 확인하기</a></li><li><a href="<?php echo esc_url( $links['car_cost'] ); ?>">자가용을 이용할 때 드는 월 유지비</a></li></ul>
		</section>

		<section id="guide-home" class="livingtmw-life-guide__section">
			<div><span>STEP 2</span><h2>집은 월세보다 전력·물·이동 조건을 함께 봅니다</h2></div>
			<p>정전이 잦은 건물은 발전기 가동 범위와 별도 요금에 따라 생활의 질과 비용이 크게 달라집니다. 계약 전에 에어컨과 엘리베이터까지 백업 전력이 공급되는지, 수돗물의 색과 냄새, 물탱크 청소 주기와 필터 설치 가능 여부를 직접 확인하세요. 마트와 직장까지의 Grab 비용과 이동 시간도 사실상 주거비의 일부입니다.</p>
			<ul><li><a href="<?php echo esc_url( $links['housing'] ); ?>">한국인이 미얀마에서 집을 구할 때 확인할 것</a></li><li><a href="<?php echo esc_url( $links['rent'] ); ?>">양곤 아파트 월세와 관리비 비교</a></li></ul>
		</section>

		<section id="guide-money" class="livingtmw-life-guide__section">
			<div><span>STEP 3</span><h2>환율과 계좌는 공식 조건을 다시 확인합니다</h2></div>
			<p>미얀마에서는 환율과 금융 조건이 빠르게 달라질 수 있습니다. 온라인에서 본 숫자만 믿지 말고 허가된 은행·환전소의 당일 고시, 수수료와 실제 수령액을 확인하세요. 외국인의 계좌 개설 서류와 지점별 심사도 달라질 수 있으므로 여권, 비자, 주소와 재직 증빙을 준비하고 방문할 지점에 먼저 문의하는 편이 안전합니다.</p>
			<ul><li><a href="<?php echo esc_url( $links['exchange'] ); ?>">미얀마 현지 환전 시 확인할 기본 정보</a></li><li><a href="<?php echo esc_url( $links['bank'] ); ?>">한국인의 미얀마 은행 계좌 개설 준비</a></li></ul>
		</section>

		<section id="guide-connect" class="livingtmw-life-guide__section">
			<div><span>STEP 4</span><h2>도착 직후 사용할 통신 수단을 준비합니다</h2></div>
			<p>현지 SIM은 여권 등록과 요금제 확인이 필요하고, 주거 인터넷은 설치 가능 지역과 정전 시 공유기 사용 여부까지 확인해야 합니다. 도착 첫날 지도, 택시 호출과 연락에 사용할 데이터 요금제를 먼저 개통하고 장기 거주지에서는 건물 관리사무소와 통신사에 설치 일정과 장비 비용을 확인하세요.</p>
			<ul><li><a href="<?php echo esc_url( $links['sim'] ); ?>">미얀마 휴대폰 SIM 개통 순서</a></li><li><a href="<?php echo esc_url( $links['internet'] ); ?>">양곤 가정용 인터넷 설치 확인사항</a></li></ul>
		</section>

		<section id="guide-move" class="livingtmw-life-guide__section">
			<div><span>STEP 5</span><h2>교통과 장보기 동선을 실제로 시험합니다</h2></div>
			<p>양곤은 같은 거리라도 시간대와 우기 침수에 따라 이동 시간이 크게 달라집니다. 집을 결정하기 전에 출퇴근 시간에 실제 경로를 이동해 보고, Grab 요금과 우회로를 확인하세요. 식료품은 대형마트, 현지 시장과 배달을 품목별로 나누면 비용과 시간을 줄일 수 있습니다. 냉장 식품은 정전 이력과 포장 상태도 함께 살펴야 합니다.</p>
			<ul><li><a href="<?php echo esc_url( $links['taxi'] ); ?>">양곤 택시와 Grab 이용 방법</a></li><li><a href="<?php echo esc_url( $links['driving'] ); ?>">미얀마 자동차 운전 기본 상식</a></li><li><a href="<?php echo esc_url( $links['grocery'] ); ?>">식료품과 생필품 구매처 정리</a></li><li><a href="<?php echo esc_url( $links['shopping'] ); ?>">양곤 대형마트와 쇼핑몰 비교</a></li><li><a href="<?php echo esc_url( $links['korean_food'] ); ?>">한국 식재료를 구하는 방법</a></li></ul>
		</section>

		<aside class="livingtmw-life-guide__notice"><strong>정보를 이용하기 전에</strong><p>이 안내서는 작성자의 현지 생활 경험과 공개 자료를 바탕으로 한 출발점입니다. 환율, 요금, 법률, 입국·금융 조건과 업체 서비스는 달라질 수 있으므로 계약이나 결제 전에 관계 기관과 서비스 제공자의 최신 안내를 확인하세요.</p></aside>
	</article>
	<?php
	return (string) ob_get_clean();
}
add_shortcode( 'livingtmw_life_guide', 'livingtmw_render_life_guide' );

/**
 * Add an honest editorial note to articles without altering their source text.
 */
function livingtmw_add_article_trust_box( string $content ): string {
	if ( is_admin() || ! is_singular( 'post' ) || ! in_the_loop() || ! is_main_query() ) {
		return $content;
	}

	$published = get_the_date( 'Y.m.d' );
	$modified  = get_the_modified_date( 'Y.m.d' );
	$dates     = '<span>게시일 ' . esc_html( $published ) . '</span>';

	if ( get_the_modified_time( 'U' ) > get_the_time( 'U' ) ) {
		$dates .= '<span>최종 수정 ' . esc_html( $modified ) . '</span>';
	}

	$box = '<aside class="livingtmw-article-trust" aria-label="콘텐츠 정보">'
		. '<strong>내일의 생활 편집팀</strong>'
		. '<div class="livingtmw-article-trust__dates">' . $dates . '</div>'
		. '<p>운영자는 약 1년간 양곤 Inno City에 거주하며 ATOM, APN·MPT, KBZ Bank·KBZPay, Grab과 현지 병원 서비스를 직접 이용했습니다. 개인 경험과 공개 자료를 구분해 정리했으며 가격, 정책 및 서비스 조건은 달라질 수 있으므로 중요한 결정 전에는 관련 기관의 최신 안내를 확인해 주세요.</p>'
		. '<p><a href="' . esc_url( home_url( '/editorial-policy/' ) ) . '">콘텐츠 제작·검수 원칙</a> · <a href="mailto:help@livingtmw.com?subject=' . rawurlencode( '콘텐츠 정정 요청: ' . get_the_title() ) . '">정보 수정 제보</a></p>'
		. '</aside>';

	return $content . $box;
}
add_filter( 'the_content', 'livingtmw_add_article_trust_box', 20 );

/**
 * Keep thin utility and duplicate archive screens out of the search index.
 */
function livingtmw_quality_robots( array $robots ): array {
	if ( is_search() || is_404() || is_author() || is_date() || is_tag() ) {
		$robots['noindex'] = true;
		$robots['follow']  = true;
	}
	if ( is_page( 'today-fortune' ) ) {
		$robots['noindex'] = true;
		$robots['follow']  = true;
	}

	return $robots;
}
add_filter( 'wp_robots', 'livingtmw_quality_robots' );

/**
 * Do not advertise noindex author and tag archives in the XML sitemap.
 */
function livingtmw_quality_sitemap_provider( $provider, string $name ) {
	if ( 'users' === $name ) {
		return false;
	}

	return $provider;
}
add_filter( 'wp_sitemaps_add_provider', 'livingtmw_quality_sitemap_provider', 10, 2 );

function livingtmw_quality_sitemap_taxonomies( array $taxonomies ): array {
	unset( $taxonomies['post_tag'] );
	return $taxonomies;
}
add_filter( 'wp_sitemaps_taxonomies', 'livingtmw_quality_sitemap_taxonomies' );

/** Keep the optional fortune activity out of the editorial XML sitemap. */
function livingtmw_quality_page_sitemap_args( array $args, string $post_type ): array {
	if ( 'page' !== $post_type ) {
		return $args;
	}
	$fortune = get_page_by_path( 'today-fortune', OBJECT, 'page' );
	if ( $fortune instanceof WP_Post ) {
		$args['post__not_in'] = array_values( array_unique( array_merge( $args['post__not_in'] ?? array(), array( (int) $fortune->ID ) ) ) );
	}
	return $args;
}
add_filter( 'wp_sitemaps_posts_query_args', 'livingtmw_quality_page_sitemap_args', 10, 2 );

/**
 * Add concise homepage metadata when no SEO plugin supplies it.
 */
function livingtmw_quality_head(): void {
	echo '<meta name="google-site-verification" content="hoFp2Ja6GpJ8ISIJF2XB_74k9CPR2LuTbkXgjA27GVs">' . "\n";
	echo '<meta name="google-adsense-account" content="ca-pub-3693971488676035">' . "\n";

	if ( is_front_page() || is_home() ) {
		echo '<meta name="description" content="미얀마와 양곤의 주거, 생활비, 통신, 교통, 금융 및 쇼핑 정보를 한국어로 정리하는 생활정보 사이트입니다.">' . "\n";
		echo '<link rel="canonical" href="' . esc_url( home_url( '/' ) ) . '">' . "\n";
	} elseif ( is_category() ) {
		$category_descriptions = array(
			'양곤 한국인의 현지생활 서비스' => '양곤에서 약 1년간 생활한 한국인이 Grab, KBZPay, ATOM, APN·MPT, 주거·전기·병원 서비스를 직접 이용하며 확인한 경험을 정리합니다.',
			'미얀마 생활비'             => '미얀마와 양곤의 월 생활비, 전기료, 환율과 실제 지출 사례를 항목별로 확인합니다.',
			'미얀마 주거·생활'          => '한국인이 양곤에서 집을 구하고 월세 계약과 정전·수질 등 생활환경을 점검하는 방법을 안내합니다.',
			'미얀마 통신·금융'          => '미얀마 유심, 가정용 인터넷, 은행 계좌와 환전 이용 시 필요한 준비사항과 주의점을 정리합니다.',
			'양곤 교통·자동차'          => '양곤의 Grab 택시, 직접 운전과 자동차 유지비를 실제 이동 환경에 맞춰 안내합니다.',
			'양곤 쇼핑·생활정보'        => '양곤의 마트, 시장, 한국 식재료와 생활용품 구매 방법을 품목과 상황별로 소개합니다.',
		);
		$category_name = single_cat_title( '', false );
		$description   = $category_descriptions[ $category_name ] ?? sprintf( '%s에 관한 내일의 생활 현지 정보와 실용 안내를 모았습니다.', $category_name );
		echo '<meta name="description" content="' . esc_attr( $description ) . '">' . "\n";
		echo '<link rel="canonical" href="' . esc_url( get_category_link( get_queried_object_id() ) ) . '">' . "\n";
	} elseif ( is_singular() ) {
		$descriptions = array(
			'about-livingtmw'       => '내일의 생활 운영 목적, 현지 생활정보의 확인 범위와 정정 문의 방법을 안내합니다.',
			'editorial-policy'      => '내일의 생활이 현지 경험과 공개 자료를 확인하고 콘텐츠를 제작·수정하는 원칙입니다.',
			'advertising-disclosure'=> '내일의 생활의 광고, 제휴 콘텐츠 및 AI 설명 이미지 공개 원칙을 안내합니다.',
			'today-fortune'         => '양력·음력 생년월일 또는 12띠와 관심 분야를 선택해 오늘의 흐름을 살펴보는 무료 운세입니다. 입력 정보는 저장하거나 전송하지 않습니다.',
			'myanmar-life-guide'    => '한국인이 미얀마 생활을 준비할 때 예산, 주거, 금융, 통신, 교통과 장보기를 순서대로 확인하는 종합 안내서입니다.',
		);
		$description  = $descriptions[ get_post_field( 'post_name', get_queried_object_id() ) ] ?? '';
		if ( '' === $description ) {
			$description = wp_strip_all_tags( get_the_excerpt( get_queried_object_id() ) );
		}
		$description = wp_html_excerpt( trim( preg_replace( '/\s+/', ' ', $description ) ), 155, '…' );
		if ( '' !== $description ) {
			echo '<meta name="description" content="' . esc_attr( $description ) . '">' . "\n";
		}
	}

	$schema = array(
		'@context' => 'https://schema.org',
		'@graph'   => array(
			array(
				'@type' => 'Organization',
				'@id'   => home_url( '/#organization' ),
				'name'  => '내일의 생활 편집팀',
				'url'   => home_url( '/' ),
				'email' => 'help@livingtmw.com',
			),
			array(
				'@type'     => 'WebSite',
				'@id'       => home_url( '/#website' ),
				'url'       => home_url( '/' ),
				'name'      => get_bloginfo( 'name' ),
				'publisher' => array( '@id' => home_url( '/#organization' ) ),
			),
		),
	);

	if ( is_singular( 'post' ) ) {
		$article = array(
			'@type'            => 'Article',
			'headline'         => get_the_title(),
			'datePublished'    => get_the_date( DATE_W3C ),
			'dateModified'     => get_the_modified_date( DATE_W3C ),
			'mainEntityOfPage' => get_permalink(),
			'author'           => array(
				'@type' => 'Organization',
				'name'  => '내일의 생활 편집팀',
				'url'   => home_url( '/about-livingtmw/' ),
			),
			'publisher'        => array( '@id' => home_url( '/#organization' ) ),
		);
		if ( has_post_thumbnail() ) {
			$article['image'] = array( get_the_post_thumbnail_url( null, 'full' ) );
		}
		$schema['@graph'][] = $article;
	}

	echo '<script type="application/ld+json" id="livingtmw-structured-data">' . wp_json_encode( $schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES ) . '</script>' . "\n";
}
add_action( 'wp_head', 'livingtmw_quality_head', 5 );

/**
 * Give readers a visible path back to the topic and homepage.
 */
function livingtmw_article_breadcrumbs( string $content ): string {
	if ( is_admin() || ! is_singular( 'post' ) || ! in_the_loop() || ! is_main_query() ) {
		return $content;
	}

	$parts = array( '<a href="' . esc_url( home_url( '/' ) ) . '">홈</a>' );
	$terms = get_the_category();
	if ( ! empty( $terms ) ) {
		$parts[] = '<a href="' . esc_url( get_category_link( $terms[0] ) ) . '">' . esc_html( $terms[0]->name ) . '</a>';
	}
	$parts[] = '<span aria-current="page">' . esc_html( get_the_title() ) . '</span>';

	return '<nav class="livingtmw-breadcrumbs" aria-label="현재 위치">' . implode( '<span aria-hidden="true">›</span>', $parts ) . '</nav>' . $content;
}
add_filter( 'the_content', 'livingtmw_article_breadcrumbs', 8 );

/**
 * Offer a small, genuinely related reading path instead of a generic post list.
 */
function livingtmw_related_articles( string $content ): string {
	if ( is_admin() || ! is_singular( 'post' ) || ! in_the_loop() || ! is_main_query() ) {
		return $content;
	}

	$category_ids = wp_get_post_categories( get_the_ID() );
	$query         = new WP_Query(
		array(
			'post_type'           => 'post',
			'posts_per_page'      => 3,
			'post__not_in'        => array( get_the_ID() ),
			'category__in'        => $category_ids,
			'ignore_sticky_posts' => true,
			'no_found_rows'       => true,
		)
	);

	if ( ! $query->have_posts() ) {
		return $content;
	}

	$related = '<aside class="livingtmw-related" aria-labelledby="livingtmw-related-title"><h2 id="livingtmw-related-title">같은 주제의 생활정보</h2><ul>';
	while ( $query->have_posts() ) {
		$query->the_post();
		$related .= '<li><a href="' . esc_url( get_permalink() ) . '">' . esc_html( get_the_title() ) . '</a><span>' . esc_html( get_the_date( 'Y.m.d' ) ) . '</span></li>';
	}
	wp_reset_postdata();

	return $content . $related . '</ul></aside>';
}
add_filter( 'the_content', 'livingtmw_related_articles', 18 );

/**
 * Keep policy and operator pages out of the primary header navigation.
 * They remain crawlable and are presented together in the footer.
 */
function livingtmw_footer_page_ids(): array {
	$slugs = array(
		'개인정보처리방침',
		'이용약관',
		'문의하기',
		'about-livingtmw',
		'editorial-policy',
		'advertising-disclosure',
	);
	$ids   = array();

	foreach ( $slugs as $slug ) {
		$page = get_page_by_path( $slug, OBJECT, 'page' );
		if ( $page ) {
			$ids[] = (int) $page->ID;
		}
	}

	return $ids;
}

function livingtmw_clean_primary_menu( array $items, $args ): array {
	if ( is_admin() || empty( $args->theme_location ) || 'primary' !== $args->theme_location ) {
		return $items;
	}

	$hidden_ids = livingtmw_footer_page_ids();
	return array_values(
		array_filter(
			$items,
			static function ( $item ) use ( $hidden_ids ): bool {
				return 'page' !== $item->object || ! in_array( (int) $item->object_id, $hidden_ids, true );
			}
		)
	);
}
add_filter( 'wp_nav_menu_objects', 'livingtmw_clean_primary_menu', 10, 2 );

function livingtmw_clean_fallback_menu( array $args ): array {
	$excluded        = livingtmw_footer_page_ids();
	$current_exclude = empty( $args['exclude'] ) ? array() : array_map( 'intval', explode( ',', (string) $args['exclude'] ) );
	$args['exclude'] = implode( ',', array_unique( array_merge( $current_exclude, $excluded ) ) );
	return $args;
}
add_filter( 'wp_page_menu_args', 'livingtmw_clean_fallback_menu' );

function livingtmw_exclude_footer_pages_from_lists( array $excluded_ids ): array {
	if ( is_admin() ) {
		return $excluded_ids;
	}

	return array_values( array_unique( array_merge( $excluded_ids, livingtmw_footer_page_ids() ) ) );
}
add_filter( 'wp_list_pages_excludes', 'livingtmw_exclude_footer_pages_from_lists' );

/**
 * The custom trust navigation already contains the policy and contact paths.
 * Remove GeneratePress's older, duplicate Site info / Category / Contact row.
 */
function livingtmw_disable_duplicate_footer_widgets( $widgets ): int {
	return 0;
}
add_filter( 'generate_footer_widgets', 'livingtmw_disable_duplicate_footer_widgets' );

/**
 * Keep trust and policy pages visible from every page.
 */
function livingtmw_trust_navigation(): void {
	$links = array(
		'미얀마 생활 시작 안내' => home_url( '/myanmar-life-guide/' ),
		'사이트 소개'     => home_url( '/about-livingtmw/' ),
		'콘텐츠 제작 및 검수 원칙' => home_url( '/editorial-policy/' ),
		'광고 및 이미지 공개 원칙' => home_url( '/advertising-disclosure/' ),
		'개인정보처리방침' => home_url( '/개인정보처리방침/' ),
		'이용약관'         => home_url( '/이용약관/' ),
		'문의하기'         => home_url( '/문의하기/' ),
	);

	echo '<nav class="livingtmw-trust-nav" aria-label="사이트 신뢰 및 정책 안내"><div class="livingtmw-trust-nav__inner"><div class="livingtmw-trust-nav__heading"><span>내일의 생활 안내</span><p>운영 원칙과 이용자 안내를 확인하세요.</p></div><ul>';
	foreach ( $links as $label => $url ) {
		echo '<li><a href="' . esc_url( $url ) . '">' . esc_html( $label ) . '</a></li>';
	}
	echo '</ul></div></nav><script id="livingtmw-footer-order">(function(){var n=document.querySelector(".livingtmw-trust-nav"),f=document.querySelector(".site-footer");if(n&&f&&f.parentNode){f.parentNode.insertBefore(n,f)}}());</script>';
}
add_action( 'wp_footer', 'livingtmw_trust_navigation', 8 );

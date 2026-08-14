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
		. '<p>이 글은 미얀마 생활에 필요한 정보를 이해하기 쉽게 정리한 자료입니다. 가격, 정책 및 서비스 조건은 달라질 수 있으므로 중요한 결정 전에는 관련 기관의 최신 안내를 확인해 주세요.</p>'
		. '<p><a href="' . esc_url( home_url( '/editorial-policy/' ) ) . '">콘텐츠 제작·검수 원칙</a> · <a href="mailto:help@livingtmw.com?subject=' . rawurlencode( '콘텐츠 정정 요청: ' . get_the_title() ) . '">정보 수정 제보</a></p>'
		. '</aside>';

	return $content . $box;
}
add_filter( 'the_content', 'livingtmw_add_article_trust_box', 20 );

/**
 * Keep thin utility and duplicate archive screens out of the search index.
 */
function livingtmw_quality_robots( array $robots ): array {
	if ( is_search() || is_404() || is_author() || is_date() ) {
		$robots['noindex'] = true;
		$robots['follow']  = true;
	}

	return $robots;
}
add_filter( 'wp_robots', 'livingtmw_quality_robots' );

/**
 * Add concise homepage metadata when no SEO plugin supplies it.
 */
function livingtmw_quality_head(): void {
	if ( is_front_page() || is_home() ) {
		echo '<meta name="description" content="미얀마와 양곤의 주거, 생활비, 통신, 교통, 금융 및 쇼핑 정보를 한국어로 정리하는 생활정보 사이트입니다.">' . "\n";
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
		$schema['@graph'][] = array(
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
	}

	echo '<script type="application/ld+json" id="livingtmw-structured-data">' . wp_json_encode( $schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES ) . '</script>' . "\n";
}
add_action( 'wp_head', 'livingtmw_quality_head', 5 );

/**
 * Keep trust and policy pages visible from every page.
 */
function livingtmw_trust_navigation(): void {
	$links = array(
		'사이트 소개'     => home_url( '/about-livingtmw/' ),
		'콘텐츠 제작 원칙' => home_url( '/editorial-policy/' ),
		'개인정보처리방침' => home_url( '/개인정보처리방침/' ),
		'이용약관'         => home_url( '/이용약관/' ),
		'문의하기'         => home_url( '/문의하기/' ),
	);

	echo '<nav class="livingtmw-trust-nav" aria-label="사이트 신뢰 및 정책 안내"><span>내일의 생활 안내</span><ul>';
	foreach ( $links as $label => $url ) {
		echo '<li><a href="' . esc_url( $url ) . '">' . esc_html( $label ) . '</a></li>';
	}
	echo '</ul></nav>';
}
add_action( 'wp_footer', 'livingtmw_trust_navigation', 8 );

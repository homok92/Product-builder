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
	if ( is_search() || is_404() || is_author() || is_date() || is_tag() ) {
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

/**
 * Add concise homepage metadata when no SEO plugin supplies it.
 */
function livingtmw_quality_head(): void {
	echo '<meta name="google-site-verification" content="hoFp2Ja6GpJ8ISIJF2XB_74k9CPR2LuTbkXgjA27GVs">' . "\n";
	echo '<meta name="google-adsense-account" content="ca-pub-3693971488676035">' . "\n";

	if ( is_front_page() || is_home() ) {
		echo '<meta name="description" content="미얀마와 양곤의 주거, 생활비, 통신, 교통, 금융 및 쇼핑 정보를 한국어로 정리하는 생활정보 사이트입니다.">' . "\n";
	} elseif ( is_singular() ) {
		$descriptions = array(
			'about-livingtmw'       => '내일의 생활 운영 목적, 현지 생활정보의 확인 범위와 정정 문의 방법을 안내합니다.',
			'editorial-policy'      => '내일의 생활이 현지 경험과 공개 자료를 확인하고 콘텐츠를 제작·수정하는 원칙입니다.',
			'advertising-disclosure'=> '내일의 생활의 광고, 제휴 콘텐츠 및 AI 설명 이미지 공개 원칙을 안내합니다.',
			'today-fortune'         => '12띠와 관심 분야를 선택해 오늘의 흐름을 가볍게 살펴보는 무료 운세입니다. 입력 정보는 저장하거나 전송하지 않습니다.',
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

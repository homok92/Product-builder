<?php
/**
 * First-hand Yangon field notes and source-backed article supplements.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function livingtmw_article_image( string $file, string $alt ): string {
	$url = content_url( 'mu-plugins/livingtmw-custom/images/articles/' . $file );
	return '<figure class="livingtmw-field-image"><img src="' . esc_url( $url ) . '" alt="' . esc_attr( $alt ) . '" loading="lazy" decoding="async"><figcaption>AI로 제작한 주제 설명 이미지입니다. 실제 장소·상품의 모습과 다를 수 있습니다.</figcaption></figure>';
}

function livingtmw_sources( array $sources ): string {
	$html = '<div class="livingtmw-field-sources"><h3>공식 자료와 확인일</h3><ul>';
	foreach ( $sources as $label => $url ) {
		$html .= '<li><a href="' . esc_url( $url ) . '" target="_blank" rel="noopener noreferrer">' . esc_html( $label ) . '</a> <span>(확인: 2026-08-14)</span></li>';
	}
	return $html . '</ul></div>';
}

function livingtmw_field_update( int $post_id ): string {
	$case = '<p class="livingtmw-field-badge">2026년 8월 · 양곤 거주자가 직접 제공한 실제 사례</p>';
	$note = '<div class="livingtmw-field-note"><strong>적용 범위</strong> 금액과 조건은 작성자의 실제 경험이며 환율, 건물, 가족 구성, 사용량, 지점 심사에 따라 달라집니다. 계약·금융·환전은 실행 전에 해당 기관과 전문가에게 다시 확인하세요.</div>';

	switch ( $post_id ) {
		case 8:
			return livingtmw_article_image( 'yangon-shopping.png', '양곤의 식료품 장보기 참고 이미지' ) . $case . '<h2>실제 거주비를 숫자로 다시 계산해 보기</h2><p>작성자는 양곤 1인 생활비의 현실적인 출발점을 월 <strong>1,500,000 MMK</strong>로 잡습니다. 한식당 한 끼는 1인 약 20,000 MMK, ATOM 통신비는 월 약 25,000 MMK를 사용했습니다. 이 예산은 생활 방식에 따른 참고값이며 월세는 별도로 계산하는 편이 안전합니다.</p><div class="livingtmw-table-wrap"><table><thead><tr><th>항목</th><th>실제 사례</th><th>예외</th></tr></thead><tbody><tr><td>1인 생활비</td><td>월 약 1,500,000 MMK</td><td>월세 포함 여부와 외식 빈도에 따라 크게 변동</td></tr><tr><td>한식당</td><td>1인 약 20,000 MMK</td><td>메뉴·매장별 차이</td></tr><tr><td>통신</td><td>ATOM 월 약 25,000 MMK</td><td>데이터 사용량별 차이</td></tr><tr><td>아파트 월세</td><td>보통 USD 500~700</td><td>위치·면적·가구·전력 조건별 차이</td></tr></tbody></table></div><p>작성자가 계산할 때 사용한 체감 환율은 KRW 1,000 ≈ 3,000 MMK, USD 1 ≈ 4,400 MMK였습니다. 이는 고정 환율이나 공식 환율이 아니므로 예산표를 만들 때는 당일 합법적인 환전 창구의 실제 수령액으로 다시 계산해야 합니다.</p>' . $note . livingtmw_sources( array( '미얀마 중앙은행 공식 사이트' => 'https://www.cbm.gov.mm/', 'City Properties의 City Mart 매장 안내' => 'https://www.citypropertiesmm.com/location/yangon-city-mart/' ) );

		case 11:
			return livingtmw_article_image( 'yangon-housing.png', '양곤 아파트와 정수 필터 참고 이미지' ) . $case . '<h2>Inno City와 Thiri Condo에서 배운 집 구하기 기준</h2><p>작성자가 거주 중인 Inno City는 한국식 아파트에 가깝고 한국인 거주자가 많은 편입니다. 전기와 발전기 환경이 안정적인 반면, 장을 보려면 차를 이용해야 하는 점은 단점입니다. 부모님이 거주했던 오래된 Thiri Condo에서는 물 위생이 만족스럽지 않았습니다. 그래서 오래된 건물뿐 아니라 새 아파트도 입주 전에 수질과 물탱크 관리 상태를 확인하고 <strong>주방·샤워 필터</strong>를 준비하는 것을 권합니다.</p><div class="livingtmw-checklist"><h3>계약 전 현장에서 확인할 것</h3><ul><li>정전 시간, 발전기 가동 범위, kWh 단가와 별도 연료비</li><li>수돗물 색·냄새·수압, 물탱크 청소 주기와 필터 설치 가능 여부</li><li>관리비·주차비·인터넷·퇴실 청소비를 누가 부담하는지</li><li>입주 취소·중도 퇴실 시 보증금 반환 조건과 반환 기한</li><li>마트·병원까지 실제 차량 이동 시간</li></ul></div><p>부동산을 ‘50~70년 사용 계약’으로 이해했다는 현장 경험은 개별 프로젝트의 토지권리와 계약 구조에 관한 설명일 수 있으며, 미얀마의 모든 아파트에 일괄 적용되는 법칙으로 보면 안 됩니다. 외국인의 콘도미니엄 권리에도 등록 요건과 제한이 있으므로 매수 전 토지 등기, 잔여기간, 외국인 취득 가능 여부, 재판매 조건을 독립 변호사에게 확인하세요. 작성자는 매수 후 재판매가 어려운 사례를 보아 장기 체류가 확정되지 않았다면 월세를 선호합니다.</p>' . $note . livingtmw_sources( array( 'DICA/OECD 미얀마 투자정책 검토 자료' => 'https://www.dica.gov.mm/sites/dica.gov.mm/files/news-files/d7984f44-en.pdf' ) );

		case 13:
			return livingtmw_article_image( 'yangon-housing.png', '양곤 아파트 월세 생활 참고 이미지' ) . $case . '<h2>월세·보증금·공과금 실제 범위</h2><div class="livingtmw-table-wrap"><table><thead><tr><th>항목</th><th>작성자 경험</th><th>계약서 확인</th></tr></thead><tbody><tr><td>월세</td><td>USD 500~700가 흔한 출발점</td><td>달러·MMK 중 결제 통화와 적용 환율</td></tr><tr><td>보증금</td><td>월세 1개월분 사례</td><td>공제 사유, 반환일, 중도해지</td></tr><tr><td>관리·주차</td><td>아파트별 상이</td><td>월세 포함 여부</td></tr><tr><td>전기·발전기</td><td>공급 방식에 따라 큰 차이</td><td>계량기 사진과 단가를 매월 확인</td></tr></tbody></table></div><p>Inno City에서는 신생아가 있는 3인 가족이 에어컨을 상시 사용해 전기·냉방 관련 월 청구가 약 1,800,000 MMK였고, 작성자가 접한 2인 가구 사례는 약 1,000,000 MMK였습니다. 이는 관리비 전체나 모든 세대의 평균이 아니라 사용량과 건물 요금 체계가 반영된 개별 사례입니다.</p><p>계약금 송금 전에는 ‘입주가 취소되면 언제, 어떤 통화로, 얼마를 돌려받는지’를 문장으로 넣으세요. 가구·가전 고장은 사진과 인수인계표로 남기고, 영문 계약서와 현지어 계약서가 다를 때 어느 문서가 우선하는지도 정하는 것이 좋습니다.</p>' . $note . livingtmw_sources( array( 'DICA/OECD 미얀마 투자정책 검토 자료' => 'https://www.dica.gov.mm/sites/dica.gov.mm/files/news-files/d7984f44-en.pdf' ) );

		case 15:
			return livingtmw_article_image( 'yangon-electricity-internet.png', '양곤 아파트 전기와 냉방 참고 이미지' ) . $case . '<h2>일반 가정용 요금과 24시간 공급 요금은 다릅니다</h2><p>2026년 2월 1일부터 적용된 공식 고시에 따르면 일반 가정용은 사용 구간별로 1kWh당 50·100·150·300 MMK이고, 24시간 중단 없는 전용선 공급은 1kWh당 <strong>900 MMK</strong>입니다. 작성자가 Inno City에서 경험한 900 MMK/kWh는 후자와 일치합니다.</p><div class="livingtmw-table-wrap"><table><thead><tr><th>공급 유형</th><th>2026 공식 단가</th><th>주의</th></tr></thead><tbody><tr><td>가정용 1~50kWh</td><td>50 MMK/kWh</td><td>누진 구간별 계산</td></tr><tr><td>51~100 / 101~200</td><td>100 / 150 MMK/kWh</td><td>각 구간에 적용</td></tr><tr><td>201kWh 초과</td><td>300 MMK/kWh</td><td>일반 가정용</td></tr><tr><td>24시간 전용선</td><td>900 MMK/kWh</td><td>일반 가정용과 별도</td></tr></tbody></table></div><p>실사용량 670.9kWh에 900 MMK를 단순 곱하면 약 <strong>603,810 MMK</strong>입니다. 관리비, 발전기, 연료, 공용전기, 세금이나 다른 청구가 더해지면 최종 금액은 달라집니다. Inno City의 3인 가족 상시 냉방 사례는 월 약 1,800,000 MMK, 2인 가구 사례는 약 1,000,000 MMK였습니다. Thiri Condo 월 200,000~300,000 MMK는 작성자가 다른 거주자에게 들은 값이므로 참고만 하세요.</p>' . $note . livingtmw_sources( array( '미얀마 전력요금 고시 89/2026 (정부신문 PDF)' => 'https://www.moi.gov.mm/nlm/file-download/download/public/4928' ) );

		case 17:
			return livingtmw_article_image( 'yangon-esim.png', '양곤 통신 매장에서 eSIM을 개통하는 참고 이미지' ) . $case . '<h2>ATOM eSIM 개통 실제 순서</h2><ol><li>eSIM 지원 휴대폰과 여권 원본을 준비합니다.</li><li>공식 대리점에서 본인 명의 등록과 eSIM 발급을 요청합니다.</li><li>통화·문자·데이터가 모두 되는지 매장 안에서 확인합니다.</li><li>명의 등록 상태와 잔액 확인 방법을 저장합니다.</li></ol><p>작성자는 eSIM 발급에 5,000 MMK를 지불했고 대리점 방문 후 바로 등록했습니다. 월 약 25,000 MMK 사용으로 일상 통신에는 충분했습니다. ATOM 공식 안내의 <strong>SIM 등록 자체는 무료</strong>이므로, 5,000 MMK는 작성자가 경험한 eSIM 발급 비용이며 등록 수수료와 구분해야 합니다.</p><p>외국인은 여권 정보를 정확히 등록해야 하며, 명의 불일치는 통화·데이터·은행 OTP 사용에 영향을 줄 수 있습니다. 기기 호환성, eSIM 재발급 비용, 요금제는 방문 당일 확인하세요.</p>' . $note . livingtmw_sources( array( 'ATOM SIM 등록 안내' => 'https://arena.atom.com.mm/en/personal/simregistration', 'MPT SIM 등록 안내' => 'https://mpt.com.mm/en/sim-register/' ) );

		case 19:
			return livingtmw_article_image( 'yangon-electricity-internet.png', '양곤 가정의 인터넷과 백업 전원 참고 이미지' ) . $case . '<h2>APN·MPT 실제 사용과 다른 회선 비교</h2><p>작성자는 집에서 APN, 회사에서 MPT를 사용합니다. 월 80,000~100,000 MMK, 체감 속도 60~80Mbps였고 해당 설치에서는 별도 설치비 없이 빠르게 개통됐습니다. 최고 속도보다 중요한 것은 <strong>내 건물에 실제로 들어오는 회선</strong>과 정전 때 공유기·통신장비가 얼마나 유지되는지입니다.</p><div class="livingtmw-table-wrap"><table><thead><tr><th>선택지</th><th>확인된 정보</th><th>계약 전 질문</th></tr></thead><tbody><tr><td>APN</td><td>작성자 집: 60~80Mbps, 월 8~10만 MMK</td><td>해당 동·호수 설치 가능 여부</td></tr><tr><td>MPT</td><td>작성자 회사에서 사용</td><td>기업·가정 요금과 장애 대응</td></tr><tr><td>5BB</td><td>공식 선불 FTTH 상품 공개</td><td>설치비·장비·약정</td></tr><tr><td>Myanmar Net</td><td>공식 사이트에 2026 가격·설치비 공지</td><td>지역·건물별 실제 속도</td></tr></tbody></table></div><p>영상회의는 대체로 가능했지만 한국보다 느리게 느껴질 수 있었습니다. 계약 전 휴대폰으로 와이파이 속도뿐 아니라 업로드, 지연시간, 저녁 혼잡 시간도 측정하고 공유기용 UPS를 검토하세요.</p>' . $note . livingtmw_sources( array( 'Myanmar APN 공식 사이트' => 'https://myanmarapn.com/about', '5BB FTTH 선불 요금' => 'https://www.5bb.com.mm/ShowInformation/FTTHPrepaid', 'Myanmar Net 공식 사이트' => 'https://www.myanmarnet.com/' ) );

		case 21:
			return livingtmw_article_image( 'yangon-banking-exchange.png', '양곤 은행 계좌와 모바일 결제 참고 이미지' ) . $case . '<h2>KBZ 계좌 개설 실제 경험</h2><p>작성자는 미얀마 거주자로 KBZ Bank 계좌를 개설했고 일상 결제에 KBZPay를 자주 사용합니다. KBZ가 공개한 외국인 고객 안내에는 거주 외국인의 여권, 유효한 비자, 서명, 소득원 관련 서류 등이 언급됩니다.</p><div class="livingtmw-checklist"><h3>방문 전 준비 체크</h3><ul><li>여권 원본과 유효한 비자·체류 증빙</li><li>현지 주소와 연락 가능한 미얀마 전화번호</li><li>재직·사업·소득원 증빙</li><li>계좌 목적과 입출금 예정 통화</li><li>모바일뱅킹·KBZPay·OTP 활성화 가능 여부</li></ul></div><p>공식 FAQ는 2022년 문서라 현재 지점별 심사와 요구 서류가 달라질 수 있습니다. ‘거주자면 무조건 개설된다’고 단정하지 말고 방문할 지점에 먼저 확인하세요. 개설 직후 소액 이체로 계좌명, 수수료, 일일 한도와 분실 시 복구 절차를 시험하는 편이 안전합니다.</p>' . $note . livingtmw_sources( array( 'KBZ 외국인 고객 계좌 FAQ (PDF)' => 'https://www.kbzbank.com/wp-content/uploads/2022/08/Eng-General-FAQs-for-Customers_-Self-Service-BankingJuly-2022.pdf' ) );

		case 23:
			return livingtmw_article_image( 'yangon-banking-exchange.png', '양곤의 은행 환전 참고 이미지' ) . $case . '<h2>공식 환율과 체감 환율을 구분해야 합니다</h2><p>작성자가 같은 시기에 확인한 값은 은행 약 USD 1 = 3,663 MMK, 외부 시장 약 USD 1 = 4,400 MMK였습니다. 이 차이는 실제 생활비 계산에는 중요하지만, 외부 환율이 곧 합법적이거나 안전하다는 뜻은 아닙니다. <strong>중앙은행이 허가한 은행·환전소</strong>의 당일 고시, 수수료와 실제 수령액을 확인하세요.</p><p>달러 현찰은 새 지폐를 준비하는 것이 좋습니다. 작성자는 작은 흠집이나 오염이 있는 지폐가 거절되는 일을 경험했습니다. 지폐를 접거나 도장을 찍지 말고, 환전 전후 금액을 창구에서 세며 영수증을 보관하세요.</p><div class="livingtmw-field-warning"><strong>안전 주의</strong> 비공식 개인 거래는 위조지폐, 강도, 사기, 환율 규정 위반 위험이 있습니다. 이 글은 비공식 환전을 권하지 않습니다. 큰 금액은 반드시 현재 규정과 허가 여부를 확인하세요.</div>' . $note . livingtmw_sources( array( '미얀마 중앙은행 공식 사이트' => 'https://www.cbm.gov.mm/', '미얀마 중앙은행 환율 페이지' => 'https://forex.cbm.gov.mm/' ) );

		case 25:
			return livingtmw_article_image( 'yangon-taxi.png', '양곤에서 택시를 호출하고 현금으로 결제하는 참고 이미지' ) . $case . '<h2>Grab 실제 비용과 탑승 요령</h2><p>작성자는 Grab에서 현금 결제를 가장 편하게 사용하고 있으며, 교통 상황이 보통일 때 20~30분 거리의 체감 비용은 약 20,000 MMK였습니다. 그러나 공식 안내처럼 호출 가격은 거리뿐 아니라 수요·공급과 혼잡도에 따라 바뀌므로 이 값을 정액으로 보면 안 됩니다.</p><ol><li>호출 전에 승차 위치를 큰 건물 입구처럼 찾기 쉬운 곳으로 지정합니다.</li><li>차량 번호와 기사 정보를 앱에서 대조합니다.</li><li>앱에 표시된 예상 요금과 결제수단을 확인합니다.</li><li>현금은 잔돈을 준비하고, 도착 후 앱의 완료 금액을 확인합니다.</li><li>차량 정보와 이동 경로를 동행자에게 공유합니다.</li></ol><p>Grab 공식 페이지는 대기 유예시간 이후 추가 요금이 생길 수 있다고 안내합니다. 공항, 비, 출퇴근 시간에는 20,000 MMK 사례보다 훨씬 높아질 수 있습니다.</p>' . $note . livingtmw_sources( array( 'Grab Myanmar 택시 안내' => 'https://www.grab.com/mm/en/transport/taxi/', 'Grab Myanmar 운송 약관' => 'https://www.grab.com/mm/terms-policies/transport-delivery-logistics/' ) );

		case 27:
			return livingtmw_article_image( 'yangon-shopping.png', '양곤 슈퍼마켓과 재래시장 장보기 참고 이미지' ) . $case . '<h2>품목별로 구매처를 나누면 편합니다</h2><div class="livingtmw-table-wrap"><table><thead><tr><th>품목</th><th>주로 이용한 곳</th><th>이유·주의</th></tr></thead><tbody><tr><td>고기·음료</td><td>City Mart·Market Place</td><td>포장·냉장 상품 비교가 편함</td></tr><tr><td>채소·과일</td><td>현지 시장·배달</td><td>신선도와 당일 가격 확인</td></tr><tr><td>한국 수입품</td><td>대형마트·전문점</td><td>수입비용 때문에 비쌀 수 있음</td></tr><tr><td>생수·무거운 물품</td><td>배달</td><td>배송 시간·최소 주문 확인</td></tr></tbody></table></div><p>작성자 체감으로 한국에서 300원인 수입 제품이 미얀마에서는 한국 돈 환산 900~1,200원 수준이 되는 경우가 있었습니다. 모든 상품이 정확히 3~4배라는 뜻은 아니며 환율, 관세, 유통과 재고에 따라 다릅니다. 현지 채소와 과일은 시장 배달을 이용하면 이동 시간을 줄일 수 있습니다.</p><p>냉장·냉동 식품은 정전 이력과 포장 상태를 보고, 과일·채소는 수령 즉시 세척·선별하세요. 배달 주문은 대체 상품 허용 여부와 환불 조건을 먼저 확인하는 편이 좋습니다.</p>' . $note . livingtmw_sources( array( 'City Properties의 Yangon City Mart 매장 목록' => 'https://www.citypropertiesmm.com/location/yangon-city-mart/' ) );

		case 29:
			return livingtmw_article_image( 'yangon-shopping.png', '양곤 마트와 시장의 식료품 참고 이미지' ) . $case . '<h2>City Mart·Market Place·시장 비교</h2><div class="livingtmw-table-wrap"><table><thead><tr><th>구매처</th><th>잘 맞는 용도</th><th>단점</th></tr></thead><tbody><tr><td>City Mart</td><td>일상 식료품·음료·생활용품</td><td>수입품은 가격 부담</td></tr><tr><td>Market Place</td><td>수입 식재료와 비교 구매</td><td>지점·재고별 차이</td></tr><tr><td>현지 시장</td><td>채소·과일</td><td>가격·위생·품질을 직접 확인</td></tr><tr><td>배달 주문</td><td>시장 장보기와 무거운 상품</td><td>대체품·배송비·수령시간</td></tr></tbody></table></div><p>Inno City에서는 마트 이동에 차가 필요한 점이 실제 생활 동선의 단점이었습니다. 집을 고를 때 월세만 보지 말고 자주 가는 마트까지의 Grab 비용과 시간을 생활비에 포함하세요. 한국인 가정은 고기와 음료는 City Mart나 Market Place, 채소와 과일은 시장에서 나누어 사는 경우가 많다는 것이 작성자의 관찰입니다.</p><p>공식 매장 목록은 지점 위치를 찾는 출발점일 뿐 영업시간과 재고를 보장하지 않습니다. 방문 당일 지도와 매장 채널로 다시 확인하세요.</p>' . $note . livingtmw_sources( array( 'City Properties의 Yangon City Mart 매장 목록' => 'https://www.citypropertiesmm.com/location/yangon-city-mart/', 'City Mart 공식 센터 안내' => 'https://www.citypropertiesmm.com/center-group/city-mart/' ) );
	}

	return '';
}

add_filter(
	'the_content',
	static function ( string $content ): string {
		if ( ! is_singular( 'post' ) || ! in_the_loop() || ! is_main_query() ) {
			return $content;
		}
		$update = livingtmw_field_update( (int) get_the_ID() );
		if ( '' === $update ) {
			return $content;
		}
		return $content . '<section class="livingtmw-field-update" aria-label="2026년 8월 현지 경험과 공식 자료 보강">' . $update . '</section>';
	},
	15
);

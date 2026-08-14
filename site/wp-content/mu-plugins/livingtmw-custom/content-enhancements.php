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

		case 31:
			return livingtmw_article_image( 'yangon-shopping.png', '양곤에서 한국 식재료와 현지 채소를 함께 구매하는 참고 이미지' ) . $case . '<h2>실제 장보기 기준: 수입 양념과 현지 신선식품을 나눕니다</h2><p>작성자는 고기와 음료는 City Mart·Market Place, 채소와 과일은 현지 시장이나 배달을 이용합니다. 고추장·된장·김처럼 맛을 좌우하고 오래 보관되는 품목만 수입품으로 사고, 고기·달걀·채소는 현지 제품을 비교하면 비용을 줄이기 쉽습니다.</p><div class="livingtmw-table-wrap"><table><thead><tr><th>품목</th><th>추천 구매처</th><th>확인사항</th></tr></thead><tbody><tr><td>한국 양념·라면·김</td><td>Market Place·City Mart 수입 코너</td><td>유통기한·한글 라벨·포장</td></tr><tr><td>고기·음료</td><td>대형마트</td><td>냉장 상태와 소비기한</td></tr><tr><td>채소·과일</td><td>시장·배달</td><td>수령 즉시 선별·세척</td></tr><tr><td>대량 수입품</td><td>가격 비교 후 구매</td><td>품절 대비와 보관 공간</td></tr></tbody></table></div><p>한국에서 300원인 수입 제품이 현지에서 한국 돈 환산 900~1,200원 수준이 된 사례도 있었습니다. 모든 상품의 고정 배율은 아니며 환율과 재고에 따라 달라집니다.</p>' . $note . livingtmw_sources( array( 'Yangon City Mart 공식 매장 목록' => 'https://www.citypropertiesmm.com/location/yangon-city-mart/', 'GrabMart Myanmar 공식 안내' => 'https://www.grab.com/mm/en/mart/' ) );

		case 33:
			return livingtmw_article_image( 'yangon-taxi.png', '양곤 도로와 차량 이동 참고 이미지' ) . '<p class="livingtmw-field-badge">2026년 8월 · 공식 법령과 현지 환경 확인</p><h2>운전 전 반드시 확인할 법적·안전 항목</h2><p>미얀마 도로안전 및 자동차관리법은 유효한 운전면허, 차량 등록과 안전 의무를 다룹니다. 한국 면허나 국제운전면허증만으로 운전 가능한지는 체류 자격과 현재 행정 절차에 따라 달라질 수 있으므로 Road Transport Administration Department 또는 현지 보험사에 직접 확인해야 합니다.</p><div class="livingtmw-checklist"><h3>출발 전 1분 점검</h3><ul><li>면허 유효성, 차량 등록증과 보험 보장 범위</li><li>타이어 공기압·마모, 브레이크, 전조등과 와이퍼</li><li>냉각수·배터리·에어컨과 비상 삼각대</li><li>침수 구간을 피할 대체 경로와 연락처</li><li>사고 시 차량을 무리하게 이동하기 전 사진과 위치 기록</li></ul></div><p>양곤은 교통 흐름과 차선 사용이 한국과 다르게 느껴질 수 있습니다. 도착 직후에는 기사나 Grab을 이용해 동선을 익힌 뒤 직접 운전을 판단하는 것이 안전합니다.</p>' . $note . livingtmw_sources( array( '미얀마 도로안전 및 자동차관리법 2020 (MOTC PDF)' => 'https://www.motc.gov.mm/sites/default/files/rdad/Road%20Safety%20and%20Motor%20Vehicle%20Management%20Law%20%282020%29.pdf' ) );

		case 35:
			return livingtmw_article_image( 'yangon-taxi.png', '양곤 자동차 이동과 유지비 참고 이미지' ) . '<p class="livingtmw-field-badge">2026년 8월 · 비용 누락 방지용 계산표</p><h2>숫자 하나보다 내 차량 기준 월 환산표가 정확합니다</h2><div class="livingtmw-table-wrap"><table><thead><tr><th>항목</th><th>계산 방법</th><th>변동 요인</th></tr></thead><tbody><tr><td>연료비</td><td>월 주행거리 ÷ 실연비 × 당일 유가</td><td>정체·에어컨·유종</td></tr><tr><td>엔진오일·필터</td><td>교환비 ÷ 교환주기(개월)</td><td>차종·부품 수급</td></tr><tr><td>타이어·배터리</td><td>교체비 ÷ 예상 사용개월</td><td>고온·노면·보관</td></tr><tr><td>보험·등록·주차</td><td>연간비용 ÷ 12</td><td>차량가액·아파트</td></tr><tr><td>고장 적립금</td><td>정비 이력을 보고 별도 적립</td><td>연식·수입차 부품</td></tr></tbody></table></div><p>정확한 월 유지비는 차종, 월 주행거리와 주차 조건이 없으면 제시할 수 없습니다. 특히 수입차는 부품 대기기간까지 비용으로 보고, 구입 전 정비소에 소모품 가격과 조달기간을 문의하세요. 자가용이 꼭 필요하지 않다면 작성자의 Grab 사례인 20~30분 약 20,000 MMK와 월 예상 탑승 횟수를 곱해 비교할 수 있습니다.</p>' . $note . livingtmw_sources( array( '미얀마 도로안전 및 자동차관리법 2020 (MOTC PDF)' => 'https://www.motc.gov.mm/sites/default/files/rdad/Road%20Safety%20and%20Motor%20Vehicle%20Management%20Law%20%282020%29.pdf' ) );

		case 37:
			return livingtmw_article_image( 'yangon-electricity-internet.png', '양곤 우기 실내 습도와 전기 환경 참고 이미지' ) . '<p class="livingtmw-field-badge">2026년 8월 · 우기 생활 점검표</p><h2>우기는 비보다 침수·곰팡이·정전의 연결을 준비합니다</h2><div class="livingtmw-table-wrap"><table><thead><tr><th>상황</th><th>준비</th><th>하지 말아야 할 일</th></tr></thead><tbody><tr><td>강한 비·침수</td><td>공식 예보 확인, 이동 연기, 우회로</td><td>수심을 모르는 도로 진입</td></tr><tr><td>높은 습도</td><td>에어컨 제습·환기·옷장 간격</td><td>젖은 신발·옷을 닫힌 장에 보관</td></tr><tr><td>정전</td><td>조명·보조배터리·공유기 UPS</td><td>환기 없는 실내 발전기 사용</td></tr><tr><td>전자제품</td><td>방수 가방·건조함·백업</td><td>젖은 손으로 콘센트 접촉</td></tr></tbody></table></div><p>예보는 전국 평균이 아니라 양곤 지역의 당일 경보를 확인하세요. 침수된 물에는 맨홀, 전선과 오염 위험이 있을 수 있어 도보·차량 모두 우회가 우선입니다. 비가 그친 뒤에는 에어컨 필터와 창틀의 결로를 확인하세요.</p>' . $note . livingtmw_sources( array( '미얀마 기상수문국 공식 사이트' => 'https://www.dmh.gov.mm/' ) );

		case 39:
			return livingtmw_article_image( 'yangon-electricity-internet.png', '양곤 아파트의 전기와 백업 전원 참고 이미지' ) . $case . '<h2>정전 빈도는 도시 평균보다 건물의 공급 방식이 좌우합니다</h2><p>작성자가 거주하는 Inno City는 전기가 끊기지 않았고 발전기도 갖추고 있습니다. 반면 다른 아파트는 정전 시간, 발전기 가동 범위와 요금이 다릅니다. ‘발전기 있음’만 확인하지 말고 에어컨·엘리베이터·인터넷까지 공급되는지 물어야 합니다.</p><div class="livingtmw-table-wrap"><table><thead><tr><th>확인 항목</th><th>계약 전 질문</th></tr></thead><tbody><tr><td>전환 시간</td><td>정전 후 발전기 전환까지 몇 분인가?</td></tr><tr><td>공급 범위</td><td>세대 전체인가, 조명·콘센트 일부인가?</td></tr><tr><td>요금</td><td>계량 단가, 연료비, 공용전기 포함 여부는?</td></tr><tr><td>야간 운전</td><td>24시간 가동하는가, 제한 시간이 있는가?</td></tr></tbody></table></div><p>2026년 공식 고시에서 일반 가정용과 24시간 전용선 요금은 다릅니다. 작성자의 Inno City 사례인 900 MMK/kWh는 24시간 전용선 공식 단가와 일치하며 일반 가정용 단가로 일반화하면 안 됩니다.</p>' . $note . livingtmw_sources( array( '전력요금 고시 89/2026 (정부신문 PDF)' => 'https://www.moi.gov.mm/nlm/file-download/download/public/4928' ) );

		case 41:
			return livingtmw_article_image( 'yangon-shopping.png', '양곤 식료품과 음식 배달 참고 이미지' ) . '<p class="livingtmw-field-badge">2026년 8월 · 서비스 화면과 공식 안내 확인</p><h2>주문 전 주소·최종금액·결제수단을 한 화면에서 확인합니다</h2><ol><li>아파트 이름, 동·호수, 출입구와 가까운 랜드마크를 저장합니다.</li><li>식당 영업 여부와 예상 배달시간, 상품 가격·배달비·할인 후 최종액을 확인합니다.</li><li>알레르기나 제외 재료는 짧고 명확하게 적되 반영 여부를 채팅으로 확인합니다.</li><li>기사 연락을 받을 수 있도록 등록 전화번호와 데이터 연결을 유지합니다.</li><li>누락·파손은 개봉 직후 사진을 남기고 앱 고객센터로 접수합니다.</li></ol><p>GrabFood 공식 도움말은 먼저 배달 주소를 확인한 뒤 식당과 메뉴를 고르고, 장바구니에서 최종 주문을 확인하도록 안내합니다. 공식 소개 페이지에 남아 있는 베타 시절의 고정 배달비·현금 전용 문구는 현재 앱 조건과 다를 수 있으므로 <strong>앱 결제 화면을 최종 기준</strong>으로 삼았습니다.</p>' . $note . livingtmw_sources( array( 'GrabFood Myanmar 공식 도움말' => 'https://help.grab.com/passenger/en-mm/360001025888', 'GrabFood Myanmar 공식 주문 페이지' => 'https://food.grab.com/mm/en/' ) );

		case 43:
			return livingtmw_article_image( 'yangon-housing.png', '양곤 입주 생활용품과 정수 필터 참고 이미지' ) . $case . '<h2>도착 첫 주에 바로 필요한 것부터 준비합니다</h2><div class="livingtmw-table-wrap"><table><thead><tr><th>한국에서 우선 준비</th><th>현지 구매 가능</th><th>입주 즉시 확인</th></tr></thead><tbody><tr><td>개인 처방약·영문 처방전, 선호 위생용품</td><td>기본 식료품·생수·청소도구</td><td>주방·샤워 필터 규격</td></tr><tr><td>보조배터리·중요 자료 백업</td><td>우산·제습제·현지 SIM</td><td>정전·발전기·콘센트</td></tr><tr><td>구하기 어려운 한국 양념 소량</td><td>채소·과일·고기</td><td>마트·병원·배달 동선</td></tr></tbody></table></div><p>부모님이 살았던 오래된 Thiri Condo에서는 물 위생이 좋지 않았던 경험이 있어 작성자는 미얀마 아파트 생활에 필터를 권합니다. Inno City처럼 마트까지 차량이 필요한 곳도 있으므로 무거운 물품은 첫날 배달로 준비하세요. 의약품은 성분·반입 규정을 확인하고 원래 포장과 처방전을 함께 보관해야 합니다.</p>' . $note . livingtmw_sources( array( 'ATOM 외국인 SIM 등록 안내' => 'https://arena.atom.com.mm/en/personal/simregistration', 'City Mart Yangon 매장 목록' => 'https://www.citypropertiesmm.com/location/yangon-city-mart/' ) );

		case 45:
			return '<p class="livingtmw-field-badge">2026년 8월 · 의료 안전 중심 보강</p><h2>아프기 전에 병원·보험·이송 계획을 저장합니다</h2><div class="livingtmw-field-warning"><strong>응급상황</strong> 심한 흉통, 호흡곤란, 의식 저하, 마비, 심한 출혈 등은 온라인 글로 판단하지 말고 즉시 현지 응급기관과 보험사 긴급지원에 연락하세요. 이 글은 진단이나 치료 지시가 아닙니다.</div><div class="livingtmw-checklist"><h3>휴대폰에 저장할 의료 카드</h3><ul><li>여권상 영문 이름·생년월일·혈액형</li><li>알레르기, 복용약의 성분명과 용량</li><li>가까운 24시간 응급실과 이동 경로</li><li>보험 증권번호·긴급지원 전화·선결제 여부</li><li>보호자와 회사 담당자 연락처</li></ul></div><p>외국인 진료 가능 여부, 전문과, 통역, 검사 가능 시간과 보증금은 병원마다 다릅니다. 방문 전 전화로 확인하고 여권 사본, 보험서류, 결제수단을 준비하세요. 진료 후에는 영문 진단서, 처방전, 검사결과, 항목별 영수증을 받아 보험 청구와 후속 진료에 대비합니다.</p><p>미얀마 의료 접근성과 응급대응 환경은 지역·시기에 따라 변할 수 있습니다. 심각한 치료가 예상되면 보험사와 의료후송 보장도 확인하세요.</p>' . $note . livingtmw_sources( array( 'WHO Myanmar 국가·보건 정보' => 'https://www.who.int/countries/mmr/' ) );

		case 47:
			return livingtmw_article_image( 'yangon-shopping.png', '양곤 생활비와 장보기 참고 이미지' ) . $case . '<h2>한국과 비교하기 전에 실제 양곤 예산을 먼저 고정합니다</h2><div class="livingtmw-table-wrap"><table><thead><tr><th>항목</th><th>작성자 실제 기준</th><th>한국 대비 체감</th></tr></thead><tbody><tr><td>1인 월 생활비</td><td>약 1,500,000 MMK</td><td>생활 방식에 따라 달라짐</td></tr><tr><td>한식당 한 끼</td><td>1인 약 20,000 MMK</td><td>현지식보다 부담</td></tr><tr><td>아파트 월세</td><td>보통 USD 500~700</td><td>외국인 선호 주거는 저렴하지 않을 수 있음</td></tr><tr><td>ATOM 통신</td><td>월 약 25,000 MMK</td><td>데이터 사용량별 차이</td></tr><tr><td>수입제품</td><td>한국 가격의 체감 3~4배 사례</td><td>현지 대체품 사용 시 절감</td></tr></tbody></table></div><p>작성자가 당시 계산에 사용한 체감 환율은 KRW 1,000 ≈ 3,000 MMK, USD 1 ≈ 4,400 MMK입니다. 공식 고정 환율이 아니므로 비교일의 합법적인 환전 창구 실수령액으로 다시 계산해야 합니다. 에어컨을 상시 사용하는 Inno City 3인 가족의 전기·냉방 관련 청구 사례는 월 약 1,800,000 MMK였으므로 ‘미얀마는 무조건 싸다’는 결론은 맞지 않습니다.</p>' . $note . livingtmw_sources( array( '미얀마 중앙은행 공식 사이트' => 'https://www.cbm.gov.mm/', '전력요금 고시 89/2026' => 'https://www.moi.gov.mm/nlm/file-download/download/public/4928' ) );
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

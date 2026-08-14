(function () {
	'use strict';

	var weatherCard = document.querySelector('.livingtmw-weather');
	var costForm = document.querySelector('.livingtmw-cost__form');

	function number(value) {
		return Number(value) || 0;
	}

	function formatMMK(value) {
		return Math.round(value / 10000) * 10000;
	}

	function renderCost() {
		if (!costForm) return;
		var family = number(costForm.elements.family.value);
		var baseByFamily = { 1: 1500000, 2: 2300000, 3: 3000000, 4: 3700000 };
		var parts = {
			'기본 생활·식비': baseByFamily[family],
			'주거비': number(costForm.elements.housing.value),
			'전기·냉방': number(costForm.elements.electricity.value),
			'교통비': number(costForm.elements.transport.value)
		};
		var total = Object.keys(parts).reduce(function (sum, key) { return sum + parts[key]; }, 0);
		var low = formatMMK(total * 0.88);
		var high = formatMMK(total * 1.12);
		var breakdown = document.querySelector('[data-cost-breakdown]');
		document.querySelector('[data-cost-low]').textContent = low.toLocaleString('en-US');
		document.querySelector('[data-cost-high]').textContent = high.toLocaleString('en-US');
		breakdown.innerHTML = Object.keys(parts).map(function (key) {
			return '<li><span>' + key + '</span><strong>' + parts[key].toLocaleString('en-US') + ' MMK</strong></li>';
		}).join('');
	}

	function weatherLabel(code) {
		if (code === 0) return '맑음';
		if (code <= 3) return '구름 많음';
		if (code <= 48) return '안개';
		if (code <= 67) return '비';
		if (code <= 82) return '강한 소나기';
		if (code <= 99) return '뇌우';
		return '변화 가능';
	}

	function aqiLabel(aqi) {
		if (aqi <= 50) return '좋음';
		if (aqi <= 100) return '보통';
		if (aqi <= 150) return '민감군 주의';
		if (aqi <= 200) return '나쁨';
		return '매우 나쁨';
	}

	function setText(selector, value) {
		var el = weatherCard.querySelector(selector);
		if (el) el.textContent = value;
	}

	function renderWeather(weather, air) {
		var current = weather.current;
		var nowIndex = weather.hourly.time.findIndex(function (time) { return time >= current.time; });
		if (nowIndex < 0) nowIndex = 0;
		var rainValues = weather.hourly.precipitation_probability.slice(nowIndex, nowIndex + 6);
		var rainMax = Math.max.apply(null, rainValues.length ? rainValues : [0]);
		var aqi = Math.round(number(air.current.us_aqi));
		var apparent = number(current.apparent_temperature);
		var precipitation = number(current.precipitation);
		var score = 20;
		score += Math.min(25, Math.max(0, apparent - 30) * 4);
		score += Math.min(30, rainMax * 0.3);
		score += Math.min(15, precipitation * 4);
		score += Math.min(10, Math.max(0, aqi - 50) * 0.1);
		score = Math.max(0, Math.min(100, Math.round(score)));

		var level = score >= 75 ? '매우 힘든 날' : score >= 55 ? '주의가 필요한 날' : score >= 35 ? '조금 불편한 날' : '비교적 편안한 날';
		var tips = [];
		if (rainMax >= 60 || precipitation >= 2) tips.push('강한 비와 도로 침수 가능성에 대비해 이동 전 경로를 확인하세요.');
		else if (rainMax >= 30) tips.push('외출할 때 우산을 준비하는 편이 좋습니다.');
		if (apparent >= 35) tips.push('체감온도가 높습니다. 물을 자주 마시고 한낮 이동을 줄이세요.');
		if (aqi > 100) tips.push('대기질에 민감하다면 장시간 야외활동을 줄이세요.');
		if (!tips.length) tips.push('큰 기상 부담은 낮지만 출발 전 최신 예보를 한 번 더 확인하세요.');

		setText('[data-weather-score]', score);
		setText('[data-weather-level]', level);
		setText('[data-weather-summary]', weatherLabel(current.weather_code) + ' · 습도 ' + Math.round(current.relative_humidity_2m) + '%');
		setText('[data-weather-temperature]', Math.round(current.temperature_2m) + '°C');
		setText('[data-weather-apparent]', Math.round(apparent) + '°C');
		setText('[data-weather-rain]', rainMax + '%');
		setText('[data-weather-aqi]', aqiLabel(aqi) + ' ' + aqi);
		weatherCard.querySelector('[data-weather-tips]').innerHTML = tips.map(function (tip) { return '<li>' + tip + '</li>'; }).join('');
		weatherCard.querySelector('.livingtmw-weather__loading').hidden = true;
		weatherCard.querySelector('.livingtmw-weather__error').hidden = true;
		weatherCard.querySelector('.livingtmw-weather__content').hidden = false;
	}

	function loadWeather() {
		if (!weatherCard) return;
		var lat = weatherCard.dataset.latitude;
		var lon = weatherCard.dataset.longitude;
		var weatherUrl = 'https://api.open-meteo.com/v1/forecast?latitude=' + lat + '&longitude=' + lon + '&current=temperature_2m,relative_humidity_2m,apparent_temperature,precipitation,weather_code&hourly=precipitation_probability&forecast_days=2&timezone=Asia%2FYangon';
		var airUrl = 'https://air-quality-api.open-meteo.com/v1/air-quality?latitude=' + lat + '&longitude=' + lon + '&current=us_aqi,pm2_5&timezone=Asia%2FYangon';
		weatherCard.querySelector('.livingtmw-weather__loading').hidden = false;
		weatherCard.querySelector('.livingtmw-weather__error').hidden = true;
		Promise.all([fetch(weatherUrl), fetch(airUrl)]).then(function (responses) {
			if (!responses[0].ok || !responses[1].ok) throw new Error('API response failed');
			return Promise.all([responses[0].json(), responses[1].json()]);
		}).then(function (data) {
			renderWeather(data[0], data[1]);
		}).catch(function () {
			weatherCard.querySelector('.livingtmw-weather__loading').hidden = true;
			weatherCard.querySelector('.livingtmw-weather__content').hidden = true;
			weatherCard.querySelector('.livingtmw-weather__error').hidden = false;
		});
	}

	if (costForm) {
		costForm.addEventListener('change', renderCost);
		renderCost();
	}
	if (weatherCard) {
		var retry = weatherCard.querySelector('[data-weather-retry]');
		if (retry) retry.addEventListener('click', loadWeather);
		loadWeather();
	}
}());


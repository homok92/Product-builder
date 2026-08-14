(function () {
	'use strict';

	var root = document.documentElement;
	var toggle = document.querySelector('.livingtmw-theme-toggle');
	var label = toggle && toggle.querySelector('.livingtmw-theme-toggle__label');
	var preference = window.matchMedia('(prefers-color-scheme: dark)');

	if (!toggle || !label) {
		return;
	}

	function currentTheme() {
		return root.dataset.theme === 'dark' ? 'dark' : 'light';
	}

	function render(theme) {
		var dark = theme === 'dark';
		root.dataset.theme = theme;
		root.style.colorScheme = theme;
		toggle.setAttribute('aria-pressed', String(dark));
		toggle.setAttribute('aria-label', dark ? '화이트 모드로 전환' : '다크 모드로 전환');
		label.textContent = dark ? '화이트 모드' : '다크 모드';
	}

	toggle.addEventListener('click', function () {
		var nextTheme = currentTheme() === 'dark' ? 'light' : 'dark';
		localStorage.setItem('livingtmw-theme', nextTheme);
		render(nextTheme);
	});

	preference.addEventListener('change', function (event) {
		if (!localStorage.getItem('livingtmw-theme')) {
			render(event.matches ? 'dark' : 'light');
		}
	});

	render(currentTheme());
}());

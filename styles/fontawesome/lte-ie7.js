/* Load this script using conditional IE comments if you need to support IE 7 and IE 6. */

window.onload = function() {
	function addIcon(el, entity) {
		var html = el.innerHTML;
		el.innerHTML = '<span style="font-family: \'fontawesome\'">' + entity + '</span>' + html;
	}
	var icons = {
			'icon-font' : '&#xf034;',
			'icon-italic' : '&#xf033;',
			'icon-bold' : '&#xf036;',
			'icon-align-left' : '&#xf037;',
			'icon-align-center' : '&#xf038;',
			'icon-align-right' : '&#xf03b;',
			'icon-indent-left' : '&#xf040;',
			'icon-text-height' : '&#xf031;',
			'icon-pencil' : '&#xf032;',
			'icon-ok' : '&#xf00c;',
			'icon-resize-full' : '&#xf065;',
			'icon-th' : '&#xf00a;',
			'icon-move' : '&#xf047;',
			'icon-tint' : '&#xf043;',
			'icon-underline' : '&#xf0cd;',
			'icon-picture' : '&#xf03e;',
			'icon-trash' : '&#xf014;',
			'icon-info' : '&#xf129;',
			'icon-question' : '&#xf128;',
			'icon-table' : '&#xf0ce;',
			'icon-resize-horizontal' : '&#xf07e;',
			'icon-plus' : '&#xf067;',
			'icon-cut' : '&#xf0c4;'
		},
		els = document.getElementsByTagName('*'),
		i, attr, html, c, el;
	for (i = 0; ; i += 1) {
		el = els[i];
		if(!el) {
			break;
		}
		attr = el.getAttribute('data-icon');
		if (attr) {
			addIcon(el, attr);
		}
		c = el.className;
		c = c.match(/icon-[^\s'"]+/);
		if (c && icons[c[0]]) {
			addIcon(el, icons[c[0]]);
		}
	}
};
define(function (require) {
	return function () {
		return {
			name: '',
			href: '/',
			text: '',
			title: '',
			icon: '',
			icon_alt: '',
			item_class: '',
			link_class: '',
			children: [],
			linksTo: null,
			linkType: 'static',
			isCustom: true,
			isHidden: false,
			access: 'public'
		};
	};
});
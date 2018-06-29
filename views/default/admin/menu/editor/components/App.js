define(function(require) {

	var Ajax = require('elgg/Ajax');
	var Vue = require('elgg/Vue');

	var template = require('text!admin/menu/editor/components/App.html');

	Vue.component('menu-editor-app', {
		template: template,
		props: {
			menuName: {
				type: String
			},
			sectionData: {
				type: Array
			}
		},
		data: function() {
			return {
				sections: this.sectionData,
				loading: false
			};
		},
		methods: {
			addSection: function() {
				this.sections.push({
					name: 'custom' + this.sections.length,
					items: []
				});
			},
			save: function() {
				var ajax = new Ajax();

				this.loading = true;

				ajax.action('menu/editor/save', {
					data: {
						name: this.menuName,
						sections: JSON.stringify(this.sections)
					}
				}).done(function() {
					this.loading = false;
				}).fail(function() {
					this.loading = false;
				});
			}
		}
	});

});
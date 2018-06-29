define(function (require) {

	var Vue = require('elgg/Vue');

	var template = require('text!admin/menu/editor/components/Section.html');

	var ItemModel = require('admin/menu/editor/models/Item');

	Vue.component('menu-editor-section', {
		template: template,
		props: {
			section: {
				type: Object
			}
		},
		data: function () {
			return {
				draggableOptions: {
					handle: '.menu-editor__handle',
					group: {
						name: 'menu-items'
					},
					animation: 150
				}
			};
		},
		methods: {
			addItem: function () {
				this.section.items.push(ItemModel());
			},
			deleteItem: function(index) {
				Vue.delete(this.section.items, index);
			}
		}
	});

});
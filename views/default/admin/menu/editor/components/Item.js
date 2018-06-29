define(function(require) {

	var Vue = require('elgg/Vue');

	var template = require('text!admin/menu/editor/components/Item.html');

	Vue.component('menu-editor-item', {
		template: template,
		props: {
			item: {
				type: Object
			}
		},
		data: function() {
			return {
				isEditing: false,
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
			deleteItem: function() {
				this.$emit('delete');
			},
			deleteChild: function(index) {
				Vue.delete(this.item.children, index);
			},
			toggleForm: function() {
				this.isEditing = this.isEditing ? false : true;
			}
		}
	});

});
define(function (require) {

	var elgg = require('elgg');
	var Vue = require('elgg/Vue');

	var template = require('text!admin/menu/editor/components/Form.html');

	var ItemModel = require('admin/menu/editor/models/Item');

	Vue.component('menu-editor-form', {
		template: template,
		props: {
			item: {
				type: Object
			}
		},
		data: function () {
			return {
				isParent: this.item.children.length > 0,
				linkTypeOptions: [
					{
						value: 'static',
						label: elgg.echo('menu:link_type:static')
					},
					{
						value: 'entity',
						label: elgg.echo('menu:link_type:entity')
					}
				],
				accessOptions: [
					{
						value: 'public',
						label: elgg.echo('access:label:public')
					},
					{
						value: 'guest',
						label: elgg.echo('access:label:guest')
					},
					{
						value: 'logged_in',
						label: elgg.echo('access:label:logged_in')
					},
					{
						value: 'admin',
						label: elgg.echo('menu:access:admin')
					}
				],
				magicVars: [
					'user.guid',
					'user.username',
					'page_owner.guid',
					'page_owner.username'
				],
			};
		},
		methods: {
			insertVar: function (magicVar) {
				this.item.href += '{{' + magicVar + '}}' + '/';
			}
		},
		watch: {
			isParent: function (value) {
				if (value && !this.item.children.length) {
					this.item.children.push(ItemModel());
				}
			},
			item: {
				handler: function (value) {
					this.isParent = this.item.children.length > 0;
				},
				deep: true
			}
		}
	})
	;

});
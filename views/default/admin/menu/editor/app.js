define(function(require) {
	var Vue = require('elgg/Vue');

	require('elgg/components/Draggable');
	require('elgg/components/Icon');
	require('elgg/components/InputText');
	require('elgg/components/InputSelect');
	require('elgg/components/InputRadio');
	require('elgg/components/InputGuids');
	require('elgg/components/InputContentEditable');
	require('elgg/components/Button');

	require('admin/menu/editor/components/App');
	require('admin/menu/editor/components/Section');
	require('admin/menu/editor/components/Item');
	require('admin/menu/editor/components/Form');

	var vm = new Vue({
		el: '#menu-editor-app',
	});

	return vm;
});
<?php

$menu_name = get_input('menu');
if (!$menu_name) {
	throw new \Elgg\PageNotFoundException();
}

$data = \hypeJunction\Menus\MenuEditor::instance()->export($menu_name);

$loader = elgg_format_element('div', [
	'class' => 'elgg-ajax-loader',
]);

echo elgg_format_element('menu-editor-app', [
	'id' => 'menu-editor-app',
	':section-data' => json_encode(array_values($data), JSON_OBJECT_AS_ARRAY),
	'menu-name' => $menu_name,
], $loader);

elgg_load_css('animate');
elgg_load_css('menu-editor-app');
elgg_require_js('admin/menu/editor/app');

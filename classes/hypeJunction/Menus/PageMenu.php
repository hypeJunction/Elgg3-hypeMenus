<?php

namespace hypeJunction\Menus;

use Elgg\Hook;
use ElggMenuItem;

class PageMenu {

	/**
	 * Setup page menu
	 *
	 * @param Hook $hook Hook
	 */
	public function __invoke(Hook $hook) {

		$menu = $hook->getValue();
		/* @var $menu \Elgg\Menu\MenuItems */

		$menu_names = MenuEditor::instance()->getMenuNames();

		foreach ($menu_names as $menu_name) {
			$menu->add(ElggMenuItem::factory([
				'name' => "admin:menus:$menu_name",
				'text' => elgg_echo("admin:menus:$menu_name"),
				'href' => "admin/menu/edit?menu=$menu_name",
				'section' => 'menus',
				'context' => ['admin'],
			]));
		}

		if (elgg_in_context('admin')) {
			$menu->remove('configure_utilities:menu_items');
		}
	}
}

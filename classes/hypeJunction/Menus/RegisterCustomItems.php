<?php

namespace hypeJunction\Menus;

use Elgg\Hook;

class RegisterCustomItems {

	/**
	 * Setup custom menu items
	 *
	 * @param Hook $hook Hook
	 *
	 * @return void
	 */
	public function __invoke(Hook $hook) {

		$type = $hook->getType();
		list($prefix, $section_name) = explode(':', $type, 2);

		if ($prefix != 'menu') {
			return;
		}

		$config = elgg_get_config("menu:$section_name");

		if (!$config) {
			return;
		}

		$customizable = $hook->getParam('customizable', true);

		$menu = $hook->getValue();
		/* @var $menu \Elgg\Menu\MenuItems */

		$push = function (
			$item_config,
			$priority,
			$section = 'default',
			$parent_name = null
		) use ($menu, $customizable, &$push) {
			$text = elgg_extract('text', $item_config);
			$name = elgg_extract('name', $item_config);
			if (!$name) {
				$name = preg_replace('/[^a-z0-9\-]/i', ':', strtolower($text));
			}

			$icon = elgg_extract('icon', $item_config);
			$icon_alt = elgg_extract('icon_alt', $item_config);
			$title = elgg_extract('title', $item_config);
			$item_class = elgg_extract('item_class', $item_config);
			$link_class = elgg_extract('link_class', $item_config);

			$isCustom = (bool) elgg_extract('isCustom', $item_config);
			$isHidden = (bool) elgg_extract('isHidden', $item_config);
			$linkType = elgg_extract('linkType', $item_config);
			$linksTo = elgg_extract('linksTo', $item_config);
			$access = elgg_extract('access', $item_config);

			$children = elgg_extract('children', $item_config, []);

			if (!$menu->has($name) && !$isCustom && empty($children)) {
				return;
			}

			$user = elgg_get_logged_in_user_entity();
			$page_owner = elgg_get_page_owner_entity() ? : $user;

			$href = elgg_extract('href', $item_config);

			if ($customizable) {
				if ($linkType == 'entity' && $linksTo) {
					$entity = get_entity($linksTo);
					if (!$entity) {
						return;
					}
					$href = $entity->getURL();
				}

				if (!$href) {
					$href = 'javascript:';
				}

				$subs = [
					'{{user.guid}}' => $user->guid,
					'{{user.username}}' => $user->username,
					'{{page_owner.guid}}' => $page_owner->guid,
					'{{page_owner.username}}' => $page_owner->username,
				];

				$href = str_replace(array_keys($subs), array_values($subs), $href);
			}

			/* @var $item \ElggMenuItem */

			if ($menu->has($name)) {
				$item = $menu->get($name);
			} else {
				$item = \ElggMenuItem::factory([
					'name' => $name,
					'text' => elgg_language_key_exists($text) ? elgg_echo($text) : $text,
					'title' => elgg_language_key_exists($title) ? elgg_echo($title) : $title,
					'href' => $href,
				]);
			}

			if ($link_class) {
				$item->setLinkClass($link_class);
			}

			if ($item_class) {
				$item->setItemClass($item_class);
			}
			
			$item->setParentName($parent_name);
			$item->setSection($section);
			$item->setPriority($priority);

			$item->isCustom = $isCustom;
			$item->isHidden = $isHidden;
			$item->linksTo = $linksTo;
			$item->linkType = $linkType;
			$item->icon = $icon;
			$item->icon_alt = $icon_alt;

			switch ($access) {
				case 'guest' :
					if ($user) {
						$isHidden = true;
					}
					break;

				case 'logged_in' :
					if (!$user) {
						$isHidden = true;
					}
					break;

				case 'admin' :
					if (!$user || !$user->isAdmin()) {
						$isHidden = true;
					}
					break;
			}

			if ($isHidden) {
				$item->setItemClass('hidden');
			}

			$child_priority = 100;
			if (!empty($children)) {
				foreach ($children as $child) {
					$push($child, $child_priority, $section, $name);
					$child_priority += 10;
				}
			}

			$menu->add($item);
		};

		foreach ($config as $section) {
			$section_name = elgg_extract('name', $section);
			$items = elgg_extract('items', $section);
			$priority = 100;

			foreach ($items as $item) {
				$push($item, $priority, $section_name);
				$priority += 100;
			}
		}
	}
}
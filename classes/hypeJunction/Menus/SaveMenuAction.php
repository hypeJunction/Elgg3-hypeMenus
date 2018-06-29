<?php

namespace hypeJunction\Menus;

use Elgg\BadRequestException;
use Elgg\Http\ResponseBuilder;
use Elgg\Request;

class SaveMenuAction {

	/**
	 * Save menu
	 *
	 * @param Request $request Request
	 * @return ResponseBuilder
	 */
	public function __invoke(Request $request) {

		$name = $request->getParam('name');
		$sections = $request->getParam('sections');

		if (!$name || !$sections) {
			throw new BadRequestException();
		}

		$sections = json_decode($sections, true);
		elgg_save_config("menu:$name", $sections);

		$msg = elgg_echo('menu:save:success');
		return elgg_ok_response('', $msg);
	}
}
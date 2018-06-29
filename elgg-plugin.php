<?php

return [
	'bootstrap' => \hypeJunction\Menus\Bootstrap::class,

	'actions' => [
		'menu/editor/save' => [
			'controller' => \hypeJunction\Menus\SaveMenuAction::class,
			'access' => 'admin',
		],
	],
];

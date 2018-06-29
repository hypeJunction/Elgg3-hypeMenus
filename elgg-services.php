<?php

return [
	'menus.editor' => \DI\object(\hypeJunction\Menus\MenuEditor::class)
		->constructor(\DI\get('menus')),

];
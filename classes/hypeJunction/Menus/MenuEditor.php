<?php

namespace hypeJunction\Menus;

use Elgg\Di\ServiceFacade;

class MenuEditor {

	use ServiceFacade;

	/**
	 * @var \Elgg\Menu\Service
	 */
	protected $menus;

	/**
	 * Constructor
	 *
	 * @param \Elgg\Menu\Service $service
	 */
	public function __construct(\Elgg\Menu\Service $menus) {
		$this->menus = $menus;
	}

	/**
	 * {@inheritdoc}
	 */
	public static function name() {
		return 'menus.editor';
	}

	/**
	 * Get configurable menu names
	 * @return array
	 */
	public function getMenuNames() {
		$menu_names = [
			'site',
			'footer',
			'topbar',
			'walled_garden',
		];

		return elgg_trigger_plugin_hook('menus', 'menu:editor', null, $menu_names);
	}

	/**
	 * Export menu into an array
	 *
	 * @param string $menu_name Menu name
	 *
	 * @return array
	 */
	public function export($menu_name) {
		$export = [];

		$menu = $this->menus->getMenu($menu_name, [
			'customizable' => false,
		]);

		$to_array = function (\ElggMenuItem $item) use (&$to_array) {
			$icon = $icon_alt = null;

			if ($item->icon) {
				$icon = $this->mapIcon($item->icon);
			}
			if ($item->icon_alt) {
				$icon_alt = $this->mapIcon($item->icon_alt);
			}

			$site_url = elgg_get_site_url();
			$href = $item->getHref();
			if (strpos($href, $site_url) === 0) {
				$href = '/' . ltrim(substr($href, strlen($site_url)));
			}

			return [
				'name' => $item->getName(),
				'href' => $href,
				'text' => $item->getText(),
				'title' => $item->getTooltip(),
				'icon' => $icon,
				'icon_alt' => $icon_alt,
				'item_class' => $item_class,
				'link_class' => $link_class,
				'children' => array_map($to_array, $item->getChildren()),

				'linkType' => $item->linkType,
				'linksTo' => $item->linksTo,
				'isCustom' => (bool) $item->isCustom,
				'isHidden' => (bool) $item->isHidden,
				'access' => $item->access ? : 'public'
			];
		};

		$sections = $menu->getSections();

		foreach ($sections as $section) {
			$section_id = $section->getId();

			$export[$section_id] = [
				'name' => $section_id,
				'items' => [],
			];

			foreach ($section as $item) {
				/* @var $item \ElggMenuItem */

				$section_id = $item->getSection();

				$export[$section_id]['items'][] = $to_array($item);
			}
		}

		return $export;
	}

	/**
	 * Map old FA icons
	 *
	 * @param string $icon Icon name
	 * @return string
	 */
	public function mapIcon($icon) {
		$fa5 = [
			'address-book-o' => ['address-book', 'far'],
			'address-card-o' => ['address-card', 'far'],
			'area-chart' => ['chart-area', 'fas'],
			'arrow-circle-o-down' => ['arrow-alt-circle-down', 'far'],
			'arrow-circle-o-left' => ['arrow-alt-circle-left', 'far'],
			'arrow-circle-o-right' => ['arrow-alt-circle-right', 'far'],
			'arrow-circle-o-up' => ['arrow-alt-circle-up', 'far'],
			'arrows-alt' => ['expand-arrows-alt', 'fas'],
			'arrows-h' => ['arrows-alt-h', 'fas'],
			'arrows-v' => ['arrows-alt-v', 'fas'],
			'arrows' => ['arrows-alt', 'fas'],
			'asl-interpreting' => ['american-sign-language-interpreting', 'fas'],
			'automobile' => ['car', 'fas'],
			'bank' => ['university', 'fas'],
			'bar-chart-o' => ['chart-bar', 'far'],
			'bar-chart' => ['chart-bar', 'far'],
			'bathtub' => ['bath', 'fas'],
			'battery-0' => ['battery-empty', 'fas'],
			'battery-1' => ['battery-quarter', 'fas'],
			'battery-2' => ['battery-half', 'fas'],
			'battery-3' => ['battery-three-quarters', 'fas'],
			'battery-4' => ['battery-full', 'fas'],
			'battery' => ['battery-full', 'fas'],
			'bell-o' => ['bell', 'far'],
			'bell-slash-o' => ['bell-slash', 'far'],
			'bitbucket-square' => ['bitbucket', 'fab'],
			'bitcoin' => ['btc', 'fab'],
			'bookmark-o' => ['bookmark', 'far'],
			'building-o' => ['building', 'far'],
			'cab' => ['taxi', 'fas'],
			'calendar-check-o' => ['calendar-check', 'far'],
			'calendar-minus-o' => ['calendar-minus', 'far'],
			'calendar-o' => ['calendar', 'far'],
			'calendar-plus-o' => ['calendar-plus', 'far'],
			'calendar-times-o' => ['calendar-times', 'far'],
			'calendar' => ['calendar-alt', 'fas'],
			'caret-square-o-down' => ['caret-square-down', 'far'],
			'caret-square-o-left' => ['caret-square-left', 'far'],
			'caret-square-o-right' => ['caret-square-right', 'far'],
			'caret-square-o-up' => ['caret-square-up', 'far'],
			'cc' => ['closed-captioning', 'far'],
			'chain-broken' => ['unlink', 'fas'],
			'chain' => ['link', 'fas'],
			'check-circle-o' => ['check-circle', 'far'],
			'check-square-o' => ['check-square', 'far'],
			'circle-o-notch' => ['circle-notch', 'fas'],
			'circle-o' => ['circle', 'far'],
			'circle-thin' => ['circle', 'far'],
			'clock-o' => ['clock', 'far'],
			'close' => ['times', 'fas'],
			'cloud-download' => ['cloud-download-alt', 'fas'],
			'cloud-upload' => ['cloud-upload-alt', 'fas'],
			'cny' => ['yen-sign', 'fas'],
			'code-fork' => ['code-branch', 'fas'],
			'comment-o' => ['comment', 'far'],
			'commenting-o' => ['comment-alt', 'far'],
			'commenting' => ['comment-alt', 'fas'],
			'comments-o' => ['comments', 'far'],
			'credit-card-alt' => ['credit-card', 'fas'],
			'cutlery' => ['utensils', 'fas'],
			'dashboard' => ['tachometer-alt', 'fas'],
			'deafness' => ['deaf', 'fas'],
			'dedent' => ['outdent', 'fas'],
			'diamond' => ['gem', 'far'],
			'dollar' => ['dollar-sign', 'fas'],
			'dot-circle-o' => ['dot-circle', 'far'],
			'drivers-license-o' => ['id-card', 'far'],
			'drivers-license' => ['id-card', 'fas'],
			'eercast' => ['sellcast', 'fab'],
			'envelope-o' => ['envelope', 'far'],
			'envelope-open-o' => ['envelope-open', 'far'],
			'eur' => ['euro-sign', 'fas'],
			'euro' => ['euro-sign', 'fas'],
			'exchange' => ['exchange-alt', 'fas'],
			'external-link-square' => ['external-link-square-alt', 'fas'],
			'external-link' => ['external-link-alt', 'fas'],
			'eyedropper' => ['eye-dropper', 'fas'],
			'fa' => ['font-awesome', 'fab'],
			'facebook-f' => ['facebook-f', 'fab'],
			'facebook-official' => ['facebook', 'fab'],
			'facebook' => ['facebook-f', 'fab'],
			'feed' => ['rss', 'fas'],
			'file-archive-o' => ['file-archive', 'far'],
			'file-audio-o' => ['file-audio', 'far'],
			'file-code-o' => ['file-code', 'far'],
			'file-excel-o' => ['file-excel', 'far'],
			'file-image-o' => ['file-image', 'far'],
			'file-movie-o' => ['file-video', 'far'],
			'file-o' => ['file', 'far'],
			'file-pdf-o' => ['file-pdf', 'far'],
			'file-photo-o' => ['file-image', 'far'],
			'file-picture-o' => ['file-image', 'far'],
			'file-powerpoint-o' => ['file-powerpoint', 'far'],
			'file-sound-o' => ['file-audio', 'far'],
			'file-text-o' => ['file-alt', 'far'],
			'file-text' => ['file-alt', 'fas'],
			'file-video-o' => ['file-video', 'far'],
			'file-word-o' => ['file-word', 'far'],
			'file-zip-o' => ['file-archive', 'far'],
			'files-o' => ['copy', 'far'],
			'flag-o' => ['flag', 'far'],
			'flash' => ['bolt', 'fas'],
			'floppy-o' => ['save', 'far'],
			'folder-o' => ['folder', 'far'],
			'folder-open-o' => ['folder-open', 'far'],
			'frown-o' => ['frown', 'far'],
			'futbol-o' => ['futbol', 'far'],
			'gbp' => ['pound-sign', 'fas'],
			'ge' => ['empire', 'fab'],
			'gear' => ['cog', 'fas'],
			'gears' => ['cogs', 'fas'],
			'gittip' => ['gratipay', 'fab'],
			'glass' => ['glass-martini', 'fas'],
			'google-plus-circle' => ['google-plus', 'fab'],
			'google-plus-official' => ['google-plus', 'fab'],
			'google-plus' => ['google-plus-g', 'fab'],
			'group' => ['users', 'fas'],
			'hand-grab-o' => ['hand-rock', 'far'],
			'hand-lizard-o' => ['hand-lizard', 'far'],
			'hand-o-down' => ['hand-point-down', 'far'],
			'hand-o-left' => ['hand-point-left', 'far'],
			'hand-o-right' => ['hand-point-right', 'far'],
			'hand-o-up' => ['hand-point-up', 'far'],
			'hand-paper-o' => ['hand-paper', 'far'],
			'hand-peace-o' => ['hand-peace', 'far'],
			'hand-pointer-o' => ['hand-pointer', 'far'],
			'hand-rock-o' => ['hand-rock', 'far'],
			'hand-scissors-o' => ['hand-scissors', 'far'],
			'hand-spock-o' => ['hand-spock', 'far'],
			'hand-stop-o' => ['hand-paper', 'far'],
			'handshake-o' => ['handshake', 'far'],
			'hard-of-hearing' => ['deaf', 'fas'],
			'hdd-o' => ['hdd', 'far'],
			'header' => ['heading', 'fas'],
			'heart-o' => ['heart', 'far'],
			'hospital-o' => ['hospital', 'far'],
			'hotel' => ['bed', 'fas'],
			'hourglass-1' => ['hourglass-start', 'fas'],
			'hourglass-2' => ['hourglass-half', 'fas'],
			'hourglass-3' => ['hourglass-end', 'fas'],
			'hourglass-o' => ['hourglass', 'far'],
			'id-card-o' => ['id-card', 'far'],
			'ils' => ['shekel-sign', 'fas'],
			'image' => ['image', 'far'],
			'inr' => ['rupee-sign', 'fas'],
			'institution' => ['university', 'fas'],
			'intersex' => ['transgender', 'fas'],
			'jpy' => ['yen-sign', 'fas'],
			'keyboard-o' => ['keyboard', 'far'],
			'krw' => ['won-sign', 'fas'],
			'legal' => ['gavel', 'fas'],
			'lemon-o' => ['lemon', 'far'],
			'level-down' => ['level-down-alt', 'fas'],
			'level-up' => ['level-up-alt', 'fas'],
			'life-bouy' => ['life-ring', 'far'],
			'life-buoy' => ['life-ring', 'far'],
			'life-saver' => ['life-ring', 'far'],
			'lightbulb-o' => ['lightbulb', 'far'],
			'line-chart' => ['chart-line', 'fas'],
			'linkedin-square' => ['linkedin', 'fab'],
			'linkedin' => ['linkedin-in', 'fab'],
			'long-arrow-down' => ['long-arrow-alt-down', 'fas'],
			'long-arrow-left' => ['long-arrow-alt-left', 'fas'],
			'long-arrow-right' => ['long-arrow-alt-right', 'fas'],
			'long-arrow-up' => ['long-arrow-alt-up', 'fas'],
			'mail-forward' => ['share', 'fas'],
			'mail-reply-all' => ['reply-all', 'fas'],
			'mail-reply' => ['reply', 'fas'],
			'map-marker' => ['map-marker-alt', 'fas'],
			'map-o' => ['map', 'far'],
			'meanpath' => ['font-awesome', 'fab'],
			'meh-o' => ['meh', 'far'],
			'minus-square-o' => ['minus-square', 'far'],
			'mobile-phone' => ['mobile-alt', 'fas'],
			'mobile' => ['mobile-alt', 'fas'],
			'money' => ['money-bill-alt', 'far'],
			'moon-o' => ['moon', 'far'],
			'mortar-board' => ['graduation-cap', 'fas'],
			'navicon' => ['bars', 'fas'],
			'newspaper-o' => ['newspaper', 'far'],
			'paper-plane-o' => ['paper-plane', 'far'],
			'paste' => ['clipboard', 'far'],
			'pause-circle-o' => ['pause-circle', 'far'],
			'pencil-square-o' => ['edit', 'far'],
			'pencil-square' => ['pen-square', 'fas'],
			'pencil' => ['pencil-alt', 'fas'],
			'photo' => ['image', 'far'],
			'picture-o' => ['image', 'far'],
			'pie-chart' => ['chart-pie', 'fas'],
			'play-circle-o' => ['play-circle', 'far'],
			'plus-square-o' => ['plus-square', 'far'],
			'question-circle-o' => ['question-circle', 'far'],
			'ra' => ['rebel', 'fab'],
			'refresh' => ['sync', 'fas'],
			'remove' => ['times', 'fas'],
			'reorder' => ['bars', 'fas'],
			'repeat' => ['redo', 'fas'],
			'resistance' => ['rebel', 'fab'],
			'rmb' => ['yen-sign', 'fas'],
			'rotate-left' => ['undo', 'fas'],
			'rotate-right' => ['redo', 'fas'],
			'rouble' => ['ruble-sign', 'fas'],
			'rub' => ['ruble-sign', 'fas'],
			'ruble' => ['ruble-sign', 'fas'],
			'rupee' => ['rupee-sign', 'fas'],
			's15' => ['bath', 'fas'],
			'scissors' => ['cut', 'fas'],
			'send-o' => ['paper-plane', 'far'],
			'send' => ['paper-plane', 'fas'],
			'share-square-o' => ['share-square', 'far'],
			'shekel' => ['shekel-sign', 'fas'],
			'sheqel' => ['shekel-sign', 'fas'],
			'shield' => ['shield-alt', 'fas'],
			'sign-in' => ['sign-in-alt', 'fas'],
			'sign-out' => ['sign-out-alt', 'fas'],
			'signing' => ['sign-language', 'fas'],
			'sliders' => ['sliders-h', 'fas'],
			'smile-o' => ['smile', 'far'],
			'snowflake-o' => ['snowflake', 'far'],
			'soccer-ball-o' => ['futbol', 'far'],
			'sort-alpha-asc' => ['sort-alpha-down', 'fas'],
			'sort-alpha-desc' => ['sort-alpha-up', 'fas'],
			'sort-amount-asc' => ['sort-amount-down', 'fas'],
			'sort-amount-desc' => ['sort-amount-up', 'fas'],
			'sort-asc' => ['sort-up', 'fas'],
			'sort-desc' => ['sort-down', 'fas'],
			'sort-numeric-asc' => ['sort-numeric-down', 'fas'],
			'sort-numeric-desc' => ['sort-numeric-up', 'fas'],
			'spoon' => ['utensil-spoon', 'fas'],
			'square-o' => ['square', 'far'],
			'star-half-empty' => ['star-half', 'far'],
			'star-half-full' => ['star-half', 'far'],
			'star-half-o' => ['star-half', 'far'],
			'star-o' => ['star', 'far'],
			'sticky-note-o' => ['sticky-note', 'far'],
			'stop-circle-o' => ['stop-circle', 'far'],
			'sun-o' => ['sun', 'far'],
			'support' => ['life-ring', 'far'],
			'tablet' => ['tablet-alt', 'fas'],
			'tachometer' => ['tachometer-alt', 'fas'],
			'television' => ['tv', 'fas'],
			'thermometer-0' => ['thermometer-empty', 'fas'],
			'thermometer-1' => ['thermometer-quarter', 'fas'],
			'thermometer-2' => ['thermometer-half', 'fas'],
			'thermometer-3' => ['thermometer-three-quarters', 'fas'],
			'thermometer-4' => ['thermometer-full', 'fas'],
			'thermometer' => ['thermometer-full', 'fas'],
			'thumb-tack' => ['thumbtack', 'fas'],
			'thumbs-o-down' => ['thumbs-down', 'far'],
			'thumbs-o-up' => ['thumbs-up', 'far'],
			'ticket' => ['ticket-alt', 'fas'],
			'times-circle-o' => ['times-circle', 'far'],
			'times-rectangle-o' => ['window-close', 'far'],
			'times-rectangle' => ['window-close', 'fas'],
			'toggle-down' => ['caret-square-down', 'far'],
			'toggle-left' => ['caret-square-left', 'far'],
			'toggle-right' => ['caret-square-right', 'far'],
			'toggle-up' => ['caret-square-up', 'far'],
			'trash-o' => ['trash-alt', 'far'],
			'trash' => ['trash-alt', 'fas'],
			'try' => ['lira-sign', 'fas'],
			'turkish-lira' => ['lira-sign', 'fas'],
			'unsorted' => ['sort', 'fas'],
			'usd' => ['dollar-sign', 'fas'],
			'user-circle-o' => ['user-circle', 'far'],
			'user-o' => ['user', 'far'],
			'vcard-o' => ['address-card', 'far'],
			'vcard' => ['address-card', 'fas'],
			'video-camera' => ['video', 'fas'],
			'vimeo' => ['vimeo-v', 'fab'],
			'volume-control-phone' => ['phone-volume', 'fas'],
			'warning' => ['exclamation-triangle', 'fas'],
			'wechat' => ['weixin', 'fab'],
			'wheelchair-alt' => ['accessible-icon', 'fab'],
			'window-close-o' => ['window-close', 'far'],
			'won' => ['won-sign', 'fas'],
			'y-combinator-square' => ['hacker-news', 'fab'],
			'yc-square' => ['hacker-news', 'fab'],
			'yc' => ['y-combinator', 'fab'],
			'yen' => ['yen-sign', 'fas'],
			'youtube-play' => ['youtube', 'fab'],
			'youtube-square' => ['youtube', 'fab'],
		];

		if (!array_key_exists($icon, $fa5)) {
			return $icon;
		}

		return $fa5[$icon][0];
	}
}
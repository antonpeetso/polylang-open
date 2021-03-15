<?php
// Code from https://github.com/maru3l/wp-rest-polylang

class polylang_open_WP_REST
{

	static $instance = false;

	private function __construct() {
		// Check if polylang is installed
		require_once ABSPATH . 'wp-admin/includes/plugin.php';

		if (!is_plugin_active('polylang/polylang.php')) {
			return;
		}

		add_action('rest_api_init', array($this, 'init'), 0);
	}

	public static function getInstance() {
		if ( !self::$instance )
			self::$instance = new self;
		return self::$instance;
	}

	public function init() {
		global $polylang;

		if (isset($_GET['polylang_lang'])) {
			$current_lang = $_GET['polylang_lang'];

			$polylang->curlang = $polylang->model->get_language($current_lang);
		}

		$post_types = get_post_types( array( 'public' => true ), 'names' );

		foreach( $post_types as $post_type ) {
			if (pll_is_translated_post_type( $post_type )) {
				self::register_api_field($post_type);
			}
		}

		add_action('rest_api_init', function () {
			register_rest_route( 'polylang-open/v1', '/languages', array(
				'methods' => 'GET',
				'callback' => 'polylang_open_get_languages',
			));
		});

		function polylang_open_get_languages() {
			return pll_languages_list(array('fields' => 'slug'));
		}
	}

	public function register_api_field($post_type) {
		register_rest_field(
			$post_type,
			"polylang_lang",
			array(
				"get_callback" => array( $this, "get_current_lang" ),
				"schema" => null
			)
		);

		register_rest_field(
			$post_type,
			"polylang_translations",
			array(
				"get_callback" => array( $this, "get_translations"  ),
				"schema" => null
			)
		);
	}

	public function get_current_lang( $object ) {
		return pll_get_post_language($object['id'], 'slug');
	}

	public function get_translations( $object ) {
		$translations = pll_get_post_translations($object['id']);

		return array_reduce($translations, function ($carry, $translation) {
			$item = array(
				'slug' => pll_get_post_language($translation, 'slug'),
				'id' => $translation
			);

			array_push($carry, $item);

			return $carry;
		}, array());
	}
}

$polylang_open_WP_REST = polylang_open_WP_REST::getInstance();
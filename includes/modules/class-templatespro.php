<?php
/**
 * Class Templates.
 *
 * Elementor widget that inserts an embbedable content into the page, from any given URL.
 *
 * @since 1.0.0
 * @package Taman Kit.
 */

namespace TamanKitPro\Modules;

/**
 * Templates class
 */
class TemplatesPro {
	/**
	 * Current theme template
	 *
	 * @var String
	 */
	public $template;

	/**
	 * Get Templatet
	 *
	 * @param string $templat folder name.
	 * @param string $file    filename.
	 */
	public function get_templatet( $templat, $file ) {

		$templatepath = TAMAN_KIT_PRO_DIR . '/includes/modules/template/templates/';
		/*'/includes/modules/template/templates/progress/circle.php' */

		$path = $templatepath . $templat . '/' . $file . '.php';

		return $path;

	}
}

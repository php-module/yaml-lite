<?php
/**
 * @version 2.0
 * @author Sammy
 *
 * @keywords Samils, ils, php framework
 * -----------------
 * @namespace Sammy\Packs\YamlLite
 * - Autoload, application dependencies
 */
namespace Sammy\Packs\YamlLite {
  use Sammy\Packs\IncludeAll;

	defined('YAML_LITE_ROOT') or define (
    'YAML_LITE_ROOT', __DIR__
  );

  $autoload_file_path = __DIR__ . '/vendor/autoload.php';

  if (is_file ($autoload_file_path)) {
    require_once $autoload_file_path;
  }
	/**
	 * Make sure the module base internal class is not
	 * declared in the php global scope defore creating
	 * it.
	 */
	#$includeAll = require (dirname (__FILE__) .
  #  '/vendor/php_modules/include-all/index.php'
  #);
  $includeAll = requires ('include-all');
	/**
	 * Autoload includeAll extensions
	 */
	$includeAll->includeAll ('./core');
}

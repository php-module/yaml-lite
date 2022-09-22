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
	defined ('YAML_LITE_ROOT') or define ('YAML_LITE_ROOT', __DIR__);

  $autoload_file_path = __DIR__ . '/vendor/autoload.php';

  if (is_file ($autoload_file_path)) {
    require_once $autoload_file_path;
  }
}

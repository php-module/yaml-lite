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
  use php\module as phpmodule;

  include_once __DIR__ . '/autoload.php';
	/**
	 * @var InPHPModuleContext
   *
   * A boolean value indicating if the
   * current script is running or not
   * in a php module context.
   * Ensure this by fetching for a
   * '$module' object containg an
   * 'exports' property.
   *
	 */
  $InPHPModuleContext = ( boolean )(
    isset ($module) &&
    is_object ($module) &&
    $module instanceof phpmodule
  );
  /**
   * Alternate the returning way
   */
  if ( $InPHPModuleContext ) {
    $module->exports = new Base;
  }

  return new Base;
}

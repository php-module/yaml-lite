<?php
/**
 * @version 2.0
 * @author Sammy
 *
 * @keywords Samils, ils, php framework
 * -----------------
 * @namespace Sammy\Packs\YamlLite\Attr
 * - Autoload, application dependencies
 */
namespace Sammy\Packs\YamlLite\Attr {
  /**
   * Make sure the yamlite attribute base internal
   * class is not declared in the php global scope
   * before creating it.
   * It ensures that the script flux is not interrupted
   * when trying to run the current command by the cli
   * API.
   */
  if (!trait_exists('Sammy\\Packs\\YamlLite\\Attr\\FileSystem')) {
  trait FileSystem {

    protected static function FileContent ($filePath = '') {
      if (self::FileExists ($filePath)) {
        return file_get_contents ($filePath);
      }
    }

    protected static function FileExists ($filePath = '') {
      return is_string ($filePath) && is_file ($filePath);
    }

    protected static function FileExtension ($filePath = '') {
      return strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
    }
  }}
}

<?php
/**
 * @version 2.0
 * @author Sammy
 *
 * @keywords Samils, ils, php framework
 * -----------------
 * @package Sammy\Packs\YamlLite
 * - Autoload, application dependencies
 *
 * MIT License
 *
 * Copyright (c) 2020 Ysare
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 */
namespace Sammy\Packs\YamlLite {
  # \Samils\dir_boot ('./exts');
  /**
   * Make sure the module base internal class is not
   * declared in the php global scope defore creating
   * it.
   * It ensures that the script flux is not interrupted
   * when trying to run the current command by the cli
   * API.
   */
  if (!class_exists('Sammy\Packs\YamlLite\Base')){
  /**
   * @class Base
   * Base internal class for the
   * YamlLite module.
   * -
   * This is (in the ils environment)
   * an instance of the php module,
   * wich should contain the module
   * core functionalities that should
   * be extended.
   * -
   * For extending the module, just create
   * an 'exts' directory in the module directory
   * and boot it by using the ils directory boot.
   * -
   * \Samils\dir_boot ('./exts');
   */
  class Base {
    use Attr\Hooks\Runner\Base;
    use Attr\YamlStrParser;
    use Attr\FileSystem;
    use Attr\Evalue;
    use Attr\Block;
    use Attr\Tab;

    /**
     * @method __construct
     * - Library constructor
     */
    public function __construct(){
    }

    function parse ($file = null) {
      return $this->parse_yaml_file ( $file );
    }

    function parse_yaml ($data = null, $t = 1) {
      if (!(is_int($t) && in_array($t, range(1, 3)))) {
        return null;
      }

      if ($t == 1) {
        return call_user_func_array (
          [ $this, 'parse_yaml_file' ],
          [ $data, debug_backtrace () ]
        );
      } elseif ($t == 2) {
        return call_user_func_array (
          [ $this, 'parse_yaml_str' ],
          [ $data, debug_backtrace () ]
        );
      }
    }

    function parse_yaml_file ($file = null) {
      $backTrace = debug_backtrace ();

      if (func_num_args () >= 1) {
        $lastArgument = func_get_arg (
          -1 + func_num_args ()
        );

        if (self::validTrace ($lastArgument)) {
          $backTrace = $lastArgument;
        }
      }

      $file = $this->realFilePath ( $file, $backTrace );

      if (is_string ($file) && preg_match('/^(\.+\/+)/', $file)) {
        $file = $this->relativeFilePath ( $file, $backTrace );
      }

      $filteredFileName = self::runFileNameHook (
        $file, [ 'stackTrace' => $backTrace [ 0 ] ]
      );

      if (is_string ($filteredFileName) && self::FileExists ($filteredFileName)) {
        $file = $filteredFileName;
      }
      /**
       * In Alternate
       * : use a hook a feature to provide a different way
       * for getting the absolute file path from any other
       * different way
       else {
        $file = Saml::ReadPath ( $file );
      }
      */

      if (!(is_string ($file) && self::FileExists($file))) {
        return null;
      }

      #print_r([ $this->parse_yaml_str(self::FileContent($file)) ]);

      #exit (0);
      return $this->parse_yaml_str (self::FileContent($file));
    }

    function parse_yaml_string(){
      return call_user_func_array([$this, 'parse_yaml_str'],
        func_get_args()
      );
    }

    private function relativeFilePath ($file, $backTrace) {
      $trace = $backTrace [ 0 ];

      $fileDirectory = dirname ($trace['file']);

      $fileAbsolutePath = $fileDirectory . (
        DIRECTORY_SEPARATOR . $file
      );

      return self::realFilePath ($fileAbsolutePath);
    }

    private function realFilePath ($fileName) {
      if ( is_string ($fileName) ) {
        $ext = self::FileExtension ($fileName);
        $ext = !empty(trim($ext)) ? $ext : ['yaml', 'yml'];

        $fileBaseName = preg_replace (
          '/\.y(a|)ml$/i', '', $fileName
        );

        if (is_string ($ext)) {
          return ($fileBaseName . '.' . $ext);
        }

        foreach ($ext as $extension) {
          $fileFullName = $fileBaseName . '.' . $extension;
          if (self::FileExists ($fileFullName)) {
            return $fileFullName;
          }
        }

        return $fileName;
      }
    }

    private static function validTrace ($backTrace) {
      return ( boolean ) (
        is_array ($backTrace) &&
        isset ($backTrace [ 0 ]) &&
        is_array ($backTrace [ 0 ]) &&
        isset ($backTrace [ 0 ]['file']) &&
        is_string ($f = $backTrace [ 0 ][ 'file' ])
      );
    }
  }}
}

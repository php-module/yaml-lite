<?php
/**
 * @version 2.0
 * @author Sammy
 *
 * @keywords Samils, ils, php framework
 * -----------------
 * @namespace Sammy\Packs\YamlLite\Attr\YAMLStringParser\TabSizeReader
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
namespace Sammy\Packs\YamlLite\Attr\YAMLStringParser\TabSizeReader {
  # \Samils\dir_boot ('./exts');
  /**
   * Make sure the module base internal class is not
   * declared in the php global scope defore creating
   * it.
   * It ensures that the script flux is not interrupted
   * when trying to run the current command by the cli
   * API.
   */
  if (!trait_exists('Sammy\Packs\YamlLite\Attr\YAMLStringParser\TabSizeReader\Base')){
  /**
   * @trait Base
   * Base internal class for the
   * TabSizeReader module.
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
  trait Base {
    protected function getNextLineTabSize ($yml_file_lines, $i, $yml_file_tab_size) {
      $ymlFileLinesNum = count ( $yml_file_lines );

      for ( ; $i < $ymlFileLinesNum; $i++ ) {
        $line = $yml_file_lines [ $i ];

        $isTheCurrentAnEmptyLine = ( boolean ) (
          empty ( $line ) ||
          preg_match ('/^#/', trim ($line))
        );

        if ( $isTheCurrentAnEmptyLine ) {
          continue;
        }

        return $this->line_tab_lv (
          $this->tab_replace( $line, $yml_file_tab_size )
        );
      }
    }

    protected function getFileTabSize ($yml_file_lines) {
      $yml_file_lines_count = count ($yml_file_lines);

      for ( $i = 0; $i < $yml_file_lines_count; $i++ ) {
        $current_line = $yml_file_lines[ $i ];

        $isTheCurrentAnEmptyLine = ( boolean )(
          empty (trim ($current_line)) ||
          preg_match ('/^#/', trim ($current_line))
        );

        if ( !$isTheCurrentAnEmptyLine ) {
          $lineStartSpacesRe = '/^(\s+)/';

          if (preg_match ($lineStartSpacesRe, $current_line, $match)) {
            return ( int )( strlen($match [0]) );
          }
        }
      }

      return 2;
    }
  }}
}

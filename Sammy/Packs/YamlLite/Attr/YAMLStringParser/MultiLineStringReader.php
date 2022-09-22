<?php
/**
 * @version 2.0
 * @author Sammy
 *
 * @keywords Samils, ils, php framework
 * -----------------
 * @namespace Sammy\Packs\YamlLite\Attr\YAMLStringParser\MultiLineStringReader
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
namespace Sammy\Packs\YamlLite\Attr\YAMLStringParser\MultiLineStringReader {
  /**
   * Make sure the module base internal class is not
   * declared in the php global scope defore creating
   * it.
   * It ensures that the script flux is not interrupted
   * when trying to run the current command by the cli
   * API.
   */
  if (!trait_exists('Sammy\Packs\YamlLite\Attr\YAMLStringParser\MultiLineStringReader\Base')){
  /**
   * @trait Base
   * Base internal class for the
   * MultiLineStringReader module.
   * -
   * This is (in the ils environment)
   * an instance of the php module,
   * whish should contain the module
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
    /**
     * @method array readMultiLineString
     *
     * Read multi line string and parse it to
     * a single or multi line string according
     * to the used 'stringKey'.
     *
     * @param array $yamlFileLines
     *
     * - An array containing the yaml document
     *   content. Each element in the array should
     *   be a line of the yaml document.
     *
     * @param int $tabSize
     *
     * - An integer value indicating the current
     *   line tab level of identation.
     *   This should be used to get only the children
     *   contents of the current key in the document,
     *   avoiding to get what ever is outside it.
     *
     * @param string $stringkey
     *
     * - A string used to identify the final string
     *   to be used as the value for the current key.
     *   It should have two valid values:
     *
     *   | -> For a multiline string
     *   > -> For a single line string
     *
     * @param int $YamlFileTabSize
     *
     * - An integer value indicating the global tab size
     *   use in the current yaml document.
     *   On condition that YamlLite supports any used tabSize
     *   The first identation tabSize should be used as a reference
     *   for knowing the global tab size in the current
     *   yaml document.
     */
    protected function readMultiLineString ($yamlFileLines, $tabSize, $stringKey = '|', $YamlFileTabSize = 2) {
      $yamlFileLinesCount = count ( $yamlFileLines );

      $lineEnd = ' ';
      $multilineString = '';

      if ($stringKey === '>') {
        $lastPregReplaceRe = '/\s+/i';
      } else {
        $lineEnd = "\n";
        $lastPregReplaceRe = '/(\n+)$/';
      }

      for ($i = 0; $i < $yamlFileLinesCount; $i++ ) {
        $currentLine = $yamlFileLines [ $i ];

        $currentLineTabSize = $this->line_tab_lv (
          $this->tab_replace ( $currentLine, $YamlFileTabSize )
        );

        if ( $currentLineTabSize < $tabSize ) {
          break;
        }

        $multilineString .= ($currentLine . $lineEnd);
      }

      return trim (preg_replace ($lastPregReplaceRe, ' ', $multilineString));
    }
  }}
}

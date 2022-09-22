<?php
/**
 * @version 2.0
 * @author Sammy
 *
 * @keywords Samils, ils, php framework
 * -----------------
 * @package Sammy\Packs\YamlLite\Attr
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
namespace Sammy\Packs\YamlLite\Attr {
  /**
   * Make sure the module base internal trait is not
   * declared in the php global scope defore creating
   * it.
   * It ensures that the script flux is not interrupted
   * when trying to run the current command by the cli
   * API.
   */
  if (!trait_exists ('Sammy\Packs\YamlLite\Attr\Evalue')) {
  /**
   * @trait Evalue
   * Base internal trait for the
   * YamlLite\Attr module.
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
   */
  trait Evalue {
    /**
     * [get_structure_end description]
     * @param  string $value
     * @return string
     */
    private function value ($value = '') {
      if (!(is_string($value) && $value))
        return $value;

      $phpIniRe = '/^(<\?(=)\s*)/';
      $phpEndRe = '/(\?\>)$/';

      if (preg_match ($phpIniRe, trim($value))) {
        # Make sure the value is a php binding
        # structure.
        if (preg_match ($phpEndRe, trim($value))) {
          # trim the 'value' variable to avoid
          # having spaces at the end or
          # beggining of it.
          $value = trim ($value);
          # Remove the php syntax delimiters
          # to have just the php code in order
          # executing it directly and return the
          # the value inside it.
          $value = trim (preg_replace ($phpIniRe, '',
            preg_replace ($phpEndRe, '', $value)
          ));

          return @eval ('return ' . $value . ';');
        }
      }

      $booleanTrueValues = preg_split ( '/\s+/',
        'true on yes'
      );
      $booleanFalseValues = preg_split ( '/\s+/',
        'false off no'
      );

      $keywords = [
        'null' => 'null',
        'none' => ''
      ];

      $booleanValues = array_merge (
        $booleanFalseValues,
        $booleanTrueValues
      );

      if (is_numeric ($value)) {
        return ( float )( $value );
      } elseif (in_array (strtolower($value), $booleanValues)) {
        return in_array (strtolower($value), $booleanTrueValues);
      } elseif (isset ($keywords [strtolower ($value)])) {
        return strtolower ($keywords [strtolower ($value)]);
      }

      $value2JsonObject = $this->objectValue2Array (
        json_decode ($value)
      );

      if ( $value2JsonObject ) {
        return $value2JsonObject;
      } elseif (!is_null (json_decode ($value))) {
        return json_decode ($value);
      } elseif (is_string ($str = $this->isStr ($value))) {
        return $this->parseStr ($str);
      }

      return $value;
    }

    protected function isStr ($data) {
      if (!(is_string ($data) && $data)) {
        return false;
      }

      $strLen = strlen ($data);

      $strDelimiter = $data [0];
      $strDelimiterEnd = $data [-1 + $strLen];

      if (!(in_array ($strDelimiter, ['"', '\'']) &&
        in_array ($strDelimiterEnd, ['"', '\'']))) {
        #for ($i = 1; $i < $strLen - 1)
        return false;
      }

      $strContent = substr ($data, 1, -2 + $strLen);

      # echo "Data => ", $strContent, "\n\n";

      $strSlices = preg_split ('/\s*/', $strContent);

      if (in_array ($strDelimiter, $strSlices)) {
        $strSlicesWidthDelimiter = preg_split ('/'.$strDelimiter.'/', $strContent);

        $newStrContent = '';

        for ($i = 0; $i < count ($strSlicesWidthDelimiter) - 1; $i++) {
          $strSliceWidthDelimiter = $strSlicesWidthDelimiter [$i];

          if (preg_match ('/(\\\)+$/', $strSliceWidthDelimiter, $match)) {

            $escapeChars = $match [0];

            $escapeCharsCount = strlen ($escapeChars);

            if ((int)($escapeCharsCount / 2) == $escapeCharsCount / 2) {
              return false;
            } else {
              #$slice = $strSliceWidthDelimiter;

              #$slice = preg_replace ('/(\\\)$/', '', $slice);

              #$slice = preg_replace ('/(\\\){2}/', '\\', $slice);

              #$newStrContent .= $slice;
            }
          } else {
            return false;
          }
        }

        #$strContent = $newStrContent;
      }

      return $strContent;
    }

    protected function parseStr ($data) {
      return $data;
    }

    protected function objectValue2Array ( $object ) {
      if (!(is_object ($object))){
        return $object;
      }

      $object = ((array)($object));
      $ob = [];

      foreach ($object as $k => $v) {
        $ob[ $k ] = $this->objectValue2Array( $v );
      }

      return is_array ($ob) ? $ob : (
        ((array)( $ob ))
      );
    }

    protected function objectValueDecode ($value, $originalvalue) {
      if (!is_null ($originalvalue)) {
        return $originalvalue;
      }

      return $value;
    }
  }}
}

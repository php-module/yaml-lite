<?php
/**
 * @version 2.0
 * @author Sammy
 *
 * @keywords Samils, ils, php framework
 * -----------------
 * @namespace Sammy\Packs\YamlLite\Attr\YAMLStringParser\PropertiChildrenGetter
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
namespace Sammy\Packs\YamlLite\Attr\YAMLStringParser\PropertiChildrenGetter {
  /**
   * Make sure the module base internal class is not
   * declared in the php global scope defore creating
   * it.
   * It ensures that the script flux is not interrupted
   * when trying to run the current command by the cli
   * API.
   */
  if (!trait_exists ('Sammy\Packs\YamlLite\Attr\YAMLStringParser\PropertiChildrenGetter\Base')) {
  /**
   * @trait Base
   * Base internal class for the
   * PropertiChildrenGetter module.
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
  trait Base {
    /**
     * @method array getPropChildren
     *
     * - Get property children.
     *   ...
     *
     */
    protected function getPropChildren ($yml_file_lines, $ltb, $yml_file_global_lines = null) {
      /**
       * @var array finalArray
       * An array containg the map of the converted
       * yaml content from the '$yml_file_lines'.
       *
       * The final array to be generated at the end
       * of the parse action.
       *
       */
      $finalArray = array ();
      /**
       * @var yml_file_global_lines
       *
       * An array containig the current yaml file
       * entire content, whish are lines list in
       * it.
       *
       * This is going to be used to reference some
       * used information when getting the file tab
       * size or the complete lines number inside it.
       *
       * Assume that the $yml_file_lines contains it
       * if the value for the $yml_file_global_lines
       * is null, it means, it was not sent as an
       * argument; so assume that its value should
       * be the same as the $yml_file_lines variable.
       *
       */
      if (!is_array ($yml_file_global_lines)) {
        $yml_file_global_lines = $yml_file_lines;
      }
      /**
       * @var  number yml_file_tab_size
       *
       * The tab size use in the current
       * file for identations.
       *
       */
      $yml_file_tab_size = $this->getFileTabSize (
        $yml_file_global_lines
      );

      # exit ( 'Tab Size => ' . $yml_file_tab_size );

      /**
       * run arround each of the yaml file lines
       * starting from the current given line
       * and going until the end of the array
       */
      foreach ($yml_file_lines as $i => $line){
        # Make sure the current line is not
        # empty or it's not a comment
        if (empty(trim($line)) || preg_match('/^#/', trim($line))) {
          # Ignore the current line on condition that
          # it may be an empty or a comment line.
          # This should not be included on the final
          # content to be generated at the end of
          # the current script flux.
          continue;
        }

        $validJsonData = ( boolean )(
          $parsedJsonData = json_decode (
            trim ( $line )
          )
        );

        if ( $validJsonData ) {
          array_push ($finalArray, $this->objectValue2Array (
            $parsedJsonData
          ));
          continue;
        }


        $commentRegularExpression = '/#([^\n]+)/';

        $line = preg_replace ($commentRegularExpression, '', $line);

        $line = $this->tab_replace($line, $yml_file_tab_size);

        $lineTabSize = $this->line_tab_lv($line);

        /**
         * @var array $encodedLine
         *
         * An array of two indexes containing
         * the new line content at first position
         * and the encoded data at the second position.
         *
         * EG: [ $newLineContent, $encodedData ]
         *
         */
        $encodedLine = $this->encodeJsonObject (
          $line
        );

        $replaceLineValue = null;

        if ( is_array ($encodedLine) ) {
          list ($line, $replaceLineValue) = $encodedLine;
        }

        #echo $lineTabSize, "\n";

        #continue;


        if (!!($lineTabSize >= ($ltb + 1))) {
          continue;
        }

        if ( $lineTabSize < $ltb ) break;

        if ( !isset($yml_file_lines[ $i + 1 ]) ) {
          $nextLineTabSize = $lineTabSize;
        } else {
          $nextLineTabSize =  $this->getNextLineTabSize (
            $yml_file_lines, $i + 1, $yml_file_tab_size
          );
        }

        #$line = preg_replace ('/^\s*-+\s*/', '', $line);


        $yamlKeyRe = '/^(.+):?(.*)$/';

        if (preg_match($yamlKeyRe, $line, $matches)) {


          # echo "$nextLineTabSize > $lineTabSize";
          // Know if the current key as children
          if ( $nextLineTabSize > $lineTabSize ) {




            $keyRe = '/^((.*):(\s+(.+)|\s+))/';
            // echo $line, " -- has children\n";
            if (preg_match ($keyRe, ($line), $keyMatch)) {
              $value = $this->objectValueDecode (
                trim ($keyMatch[ 3 ]), $replaceLineValue
              );

              $originalKeyName = preg_replace ('/(:\s*)$/', '',
                trim ($keyMatch[ 2 ])
              );

              $yml_file_next_lines = array_slice(
                $yml_file_lines, $i + 1, count (
                  $yml_file_lines
                )
              );

              # Try checking if the current object
              # value is a multiline string.
              # Assume it is truth if $keyMatch[3]
              # be equals to '|'.
              $isObjectValueAMultilineStr = (boolean) (
                isset($keyMatch[3]) && in_array (
                  trim ($keyMatch[3]),
                  preg_split ( '/\s+/',  '| >' )
                )
              );

              if ($isObjectValueAMultilineStr) {
                $propChildren = $this->readMultiLineString (
                  $yml_file_next_lines,
                  $ltb + 1,
                  trim ($keyMatch[ 3 ]),
                  $yml_file_tab_size
                );



                if ( preg_match ('/^\s*-/', $originalKeyName) ) {
                  $originalKeyName = trim (preg_replace ('/^\s*-/', '', $originalKeyName));

                  $currentKeyArrayValue = [[
                    $originalKeyName => $propChildren
                  ]];
                } else {
                  $currentKeyArrayValue = [
                    $originalKeyName => $propChildren
                  ];
                }

              } else {

                $propChildren = $this->getPropChildren (
                  $yml_file_next_lines, $ltb + 1,
                  $yml_file_global_lines
                );

                $propChildren = array_merge ($propChildren,
                  [  ]
                );


                #echo $originalKeyName, "\n";

                #exit (0);


                if (preg_match ('/^\s*-/', $originalKeyName)) {
                  $strippedOriginalKeyName = preg_replace (
                    '/^(\s*-\s*)/', '', $originalKeyName
                  );

                  $currentKeyArrayValue = [array_merge (
                    $propChildren, [ $strippedOriginalKeyName => $value ]
                  )];

                  #echo $strippedOriginalKeyName, "\n";

                  #print_r($currentKeyArrayValue);

                  #exit (0);

                } else {
                  $currentKeyArrayValue = [
                    $originalKeyName => $propChildren
                  ];
                }

              }





            } else {


              $value = null;

              $originalKeyName = preg_replace ('/(:\s*)$/', '',
                trim ( $line )
              );

              $yml_file_next_lines = array_slice(
                $yml_file_lines, $i + 1, count (
                  $yml_file_lines
                )
              );


              $propChildren = $this->getPropChildren (
                $yml_file_next_lines, $ltb + 1,
                $yml_file_global_lines
              );



              if (preg_match ('/^\s*-/', $originalKeyName)) {
                $strippedOriginalKeyName = preg_replace (
                  '/^(\s*-\s*)/', '', $originalKeyName
                );

                $currentKeyArrayValue = [array_merge (
                  $propChildren, [ $strippedOriginalKeyName => $value ]
                )];

                #echo $strippedOriginalKeyName, "\n";

                #print_r($currentKeyArrayValue);

                #exit (0);

              } else {
                $currentKeyArrayValue = [
                  $originalKeyName => $propChildren
                ];
              }




            }





            $finalArray = array_merge ($finalArray,
              $currentKeyArrayValue
            );










          } else {
            $keyRe = '/^((.*):(\s+(.+)|\s+))/';
            if (preg_match ($keyRe, $line, $keyMatch)) {
              $value = $this->objectValueDecode (
                trim ( $keyMatch[ 3 ] ), $replaceLineValue
              );

              $originalKeyName = preg_replace ('/(:\s*)$/', '',
                trim($keyMatch[ 2 ])
              );

              #$finalArray = array_merge (
              #  $finalArray, [$originalKeyName => $this->value($value)]
              #);

              #echo $originalKeyName, "\n\n";
              #print_r($keyMatch);

              #exit (0);



              if (preg_match ('/^\s*-/', $originalKeyName)) {
                $strippedOriginalKeyName = preg_replace ('/^(\s*-\s*)/', '', trim($originalKeyName));


                $finalArray = array_merge (
                  $finalArray, [[$strippedOriginalKeyName => $this->value($value)]]
                );
              } else {
                $finalArray = array_merge (
                  $finalArray, [$originalKeyName => $this->value($value)]
                );
              }

            } else {

              if ( preg_match ('/^\s*-\s*/', $line) ) {
                $lineContent = [ $this->value (preg_replace ('/^\s*-/', '',trim ($line))) ];
              } else {
                $lineContent = $this->value ( trim ($line) );
              }

              array_push($finalArray, $lineContent );
            }
          }
        }

      }

      return $finalArray;
    }
  }}
}

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

	if (!trait_exists('Sammy\\Packs\\YamlLite\\Attr\\YamlStrParser')) {
	trait YamlStrParser {
		/**
		 * [parse_yaml_str description]
		 * @param  string $str
		 * @return array
		 */
		function parse_yaml_str ($str = ''){

      #print_r(debug_backtrace());

      #exit (0);
			if (!is_string($str))
				return null;

			if (empty(trim($str)))
				return [];


      #$uncommentedString = preg_replace ('/#([^\n+]+)/', '', $str);

      #echo $uncommentedString, "\n\n\n\n";




      #exit (0);

			$yml_file_lines = preg_split('/\n+/', $str );

			$finalArray = array();

			# Keep Datas
			# ...
			# ...

			# Current index
			# - Last Element Index
			$le_i = 0;

			$i = 0;

			foreach ($yml_file_lines as $i => $line){

				if (empty(trim($line)) || preg_match('/^#/', trim($line))) {
					continue;
        }

        $re = '/#([^\n]+)/';

        $line = preg_replace ($re, '', $line);

				$line = $this->tab_replace($line);

				$lineTabSize = $this->line_tab_lv($line);

        #print_r($yml_file_lines);

        #exit (0);

        if (!!($lineTabSize >= 1)) {
          continue;
        }

        if ( !isset($yml_file_lines[ $i + 1 ]) ) {
          $nextLineTabSize = $lineTabSize;
        } else {
          $nextLineTabSize =  $this->getNextLineTabSize(
            $yml_file_lines, $i + 1
          );
        }
				// [  ]

        $yamlKeyRe = '/^(.+):?(.*)$/';

        if (preg_match($yamlKeyRe, $line, $matches)) {


          # echo "$nextLineTabSize > $lineTabSize";
          // Know if the current key as children
          if ( $nextLineTabSize > $lineTabSize ) {
            $keyRe = '/^((.*):(\s+(.+))?)+/';
            // echo $line, " -- has children\n";
            if (preg_match ($keyRe, $line, $keyMatch)) {
              #$value = trim(preg_replace ($keyRe, '', $line));

              $originalKeyName = preg_replace ('/(:\s*)$/', '',
                trim ($keyMatch[ 2 ])
              );

              $yamlKeyNameArray = !isset($keyMatch[3]) ? [] : (
                [ '--key' => trim ( $keyMatch[ 3 ] ) ]
              );

              $yml_file_next_lines = array_slice(
                $yml_file_lines, $i + 1, count (
                  $yml_file_lines
                )
              );

              $propChildren = $this->getPropChildren (
                $yml_file_next_lines, 1
              );

              if (preg_match ('/^\s*-/', $originalKeyName)) {
                $strippedOriginalKeyName = preg_replace (
                  '/^(\s*-\s*)/', '', $originalKeyName
                );

                $pregKeyRe = '/^((.*):\s+)+/';

                if (preg_match ($pregKeyRe, $strippedOriginalKeyName, $strippedOriginalKeyNameMatch)) {
                  $strippedOriginalKeyName = preg_replace ('/(\s*:\s*)$/', '', $strippedOriginalKeyNameMatch[0]);


                  $value = ltrim(preg_replace ($pregKeyRe, '', $line));
                }

                #echo $strippedOriginalKeyName, "\n\n";
                #echo $value, "\n\n";

                #exit (9);


                if ( isset ($value) ) {
                  $currentKeyArrayValue = [array_merge (
                    $propChildren, [ $strippedOriginalKeyName => $value ]
                  )];
                } else {
                  $currentKeyArrayValue = [array_merge (
                    $propChildren, [ '--key' => $strippedOriginalKeyName ]
                  )];
                }

              } else {
                $currentKeyArrayValue = array_merge (
                  [ $originalKeyName => array_merge ($propChildren, $yamlKeyNameArray) ]
                );
              }

              $finalArray = array_merge ($finalArray,
                $currentKeyArrayValue
              );
            }
          } else {
            $keyRe = '/^((.*):\s+)+/';
            if (preg_match ($keyRe, $line, $keyMatch)) {
              $value = ltrim(preg_replace ($keyRe, '', $line));

              $originalKeyName = preg_replace ('/(:\s*)$/', '',
                trim($keyMatch[ 0 ])
              );



              if (preg_match ('/^\s*-/', $originalKeyName)) {
                $strippedOriginalKeyName = preg_replace (
                  '/^(\s*-\s*)/', '', $originalKeyName
                );

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
                $lineContent = [ preg_replace ('/^\s*-/', '',trim ($line)) ];
              } else {
                $lineContent = trim($line);
              }

              array_push($finalArray, $lineContent );
            }
          }
        }


				//$i++;
			}

			return $finalArray;
		}


    protected function getPropChildren ($yml_file_lines, $ltb) {
      $finalArray = array ();
      /**
       * run arround each of the yaml file lines
       * starting from the current given line
       * and going until the end of the array
       */
      foreach ($yml_file_lines as $i => $line){
        if (empty(trim($line)) || preg_match('/^#/', trim($line))) {
          continue;
        }

        $re = '/#([^\n]+)/';

        $line = preg_replace ($re, '', $line);

        $line = $this->tab_replace($line);

        $lineTabSize = $this->line_tab_lv($line);

        if (!!($lineTabSize >= ($ltb + 1))) {
          continue;
        }

        if ( $lineTabSize < $ltb ) break;

        if ( !isset($yml_file_lines[ $i + 1 ]) ) {
          $nextLineTabSize = $lineTabSize;
        } else {
          $nextLineTabSize =  $this->getNextLineTabSize(
            $yml_file_lines, $i + 1
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
              $value = trim ($keyMatch[ 3 ]);

              $originalKeyName = preg_replace ('/(:\s*)$/', '',
                trim ($keyMatch[ 2 ])
              );

              $yml_file_next_lines = array_slice(
                $yml_file_lines, $i + 1, count (
                  $yml_file_lines
                )
              );


              $propChildren = $this->getPropChildren ( $yml_file_next_lines, $ltb + 1 );

              $propChildren = array_merge ($propChildren,
                [ '--key' => $value ]
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


              $propChildren = $this->getPropChildren ( $yml_file_next_lines, $ltb + 1 );



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
              $value = trim ( $keyMatch[ 3 ] );

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
                $lineContent = [ preg_replace ('/^\s*-/', '',trim ($line)) ];
              } else {
                $lineContent = trim($line);
              }

              array_push($finalArray, $lineContent );
            }
          }
        }

      }

      return $finalArray;
    }

    protected function getNextLineTabSize ($yml_file_lines, $i) {
      $ymlFileLinesNum = count ( $yml_file_lines );

      for ( ; $i < $ymlFileLinesNum; $i++ ) {
        $line = $yml_file_lines [ $i ];

        $emptyLine = ( boolean ) (
          empty ( $line ) ||
          preg_match ('/^#/', trim ($line))
        );

        if ( $emptyLine ) {
          continue;
        }

        return $this->line_tab_lv (
          $this->tab_replace( $line )
        );
      }
    }
	}}
}

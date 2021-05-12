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
    use YAMLStringParser\PropertiChildrenGetter\Base;
    use YAMLStringParser\MultiLineStringReader\Base;
    use YAMLStringParser\JsonObjectEncoder\Base;
    use YAMLStringParser\TabSizeReader\Base;
		/**
		 * [parse_yaml_str description]
		 * @param  string $str
		 * @return array
		 */
		public function parse_yaml_str ($str = '') {
			if ( !(is_string ($str) && !empty (trim ($str))) ) {
				return [];
      }

      $validJsonData = ( boolean )(
        $parsedJsonData = json_decode (
          trim ( $str )
        )
      );

      if ( $validJsonData ) {
        return $this->objectValue2Array (
          $parsedJsonData
        );
      }

			$yml_file_lines = preg_split('/\n+/', $str );

			$parsedYamlData = $this->getPropChildren (
        $yml_file_lines, 0
      );

      # Deleted Code
      # end

			return $parsedYamlData;
		}
	}}
}

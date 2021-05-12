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
  if (!class_exists('Sammy\\Packs\\YamlLite\\Base')) {
	class Base {
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
			$file = $this->realFilePath(
				$file
			);

			if (is_string($file) && preg_match('/^(\.+\/+)/', $file))
				$file = $this->relativeFilePath(
					$file, debug_backtrace()
				);

			return $this->parse_yaml_file (
				$file
			);
		}

		function parse_yaml ($data = null, $t = 1) {
			if(!(is_int($t) && in_array($t, range(1, 3))))
				return null;

			if ($t == 1)
				return $this->parse_yaml_file($data);
			elseif ($t == 2)
				return $this->parse_yaml_str($data);
			else
				return [];
		}

		function parse_yaml_file ($file = null) {
			$file = $this->realFilePath ( $file );

			if (is_string($file) && preg_match('/^(\.+\/+)/', $file)) {
				$file = $this->relativeFilePath( $file, debug_backtrace() );
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
				return [ ];
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
			$trace = $backTrace[ 0 ];

			$dir = dirname($trace['file']);
			if (self::FileExists($dir . '/' . $file)) {
				return ($dir . '/' . $file);
      }
		}

		private function realFilePath ($file) {
			if ( is_string ($file) ) {
				$ext = self::FileExtension ($file);
				$ext = !empty(trim($ext)) ? $ext : 'yaml';
				return preg_replace('/\.y(a|)ml$/i', '', $file) . ('.' .
					$ext
				);
			}
		}
	}}
}

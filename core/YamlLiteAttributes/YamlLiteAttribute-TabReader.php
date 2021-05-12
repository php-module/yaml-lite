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

	if (!trait_exists('Sammy\\Packs\\YamlLite\\Attr\\Tab')) {
	trait Tab {
		private function tab_replace ($line, $yamlFileTabSize) {
			$tab_2_re = '/^('.str_repeat(' ', $yamlFileTabSize).')+/';
			if(preg_match($tab_2_re, $line, $m2)){
				$sn = (count(preg_split('/\s{'.$yamlFileTabSize.'}/', $m2[0])) - 1);
				return str_repeat("\t", $sn) . ltrim($line);
			}
			return $line;
		}

		private function line_tab_lv ( $l ) {
			if (preg_match ('/^\t+/', $l, $m) ) {
				return count (preg_split('/\t/', $m[0])) - 1;
			}
			return 0;
		}
	}}
}

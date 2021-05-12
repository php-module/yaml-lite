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
		private function tab_replace ($l) {
			$tab_2_re = '/^(  )+/';
			if(preg_match($tab_2_re, $l, $m2)){
				$sn = (count(preg_split('/\s{2}/', $m2[0])) - 1);
				return str_repeat("\t", $sn) . ltrim($l);
			}
			return $l;
		}

		private function line_tab_lv ( $l ) {
			if (preg_match ('/^\t+/', $l, $m) ) {
				return count (preg_split('/\t/', $m[0])) - 1;
			}
			return 0;
		}
	}}
}

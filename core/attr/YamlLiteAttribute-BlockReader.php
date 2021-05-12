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

	if (!trait_exists('Sammy\\Packs\\YamlLite\\Attr\\Block')) {
	trait Block {
		/**
		 * [get_structure_end description]
		 * @param  [type] $yfl
		 * @param  [type] $i
		 * @param  [type] $t
		 * @return array|mixed
		 */
		private function get_structure_end ($yfl, $i, $t) {

			$r_array = [];
			$re = '/^((.+):(\s+))/';

			for ( ; $i < count($yfl); $i++ ){
				$line = $yfl[ $i ];

				if (empty(trim($line)) || preg_match('/^#/', trim($line))) {
					continue;
				}

				$line = $this->tab_replace ($line);
				$tb = $this->line_tab_lv ($line);

				if (preg_match($re, $line, $match)) {

					$k_ = preg_replace ('/:$/', '', trim ($match[0]));
					$v_ = trim (
						preg_replace($re, '', $line)
					);

					if ($tb == $t) {

						if (!empty(trim($v_))) {
							$r_array[ $k_ ] = in_array(lower($v_), ['null']) ? '' : (
								$this->value ($v_)
							);
						} else {
							$r_array[ $k_ ] = $this->get_structure_end ($yfl, ($i + 1),
								($t + 1)
							);
						}

					} elseif ($tb < $t) {
						break;
					}
				} else {

					if ( $tb === $t ) {
						$line = preg_replace('/^(-\s+)/', '',
							trim($line)
						);

						array_push($r_array,
							$this->value($line)
						);

					} elseif ($tb < $t) {
						break;
					}
				}
			}

			return $r_array;
		}
	}}
}

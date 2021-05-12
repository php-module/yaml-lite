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
	if (!trait_exists('Sammy\\Packs\\YamlLite\\Attr\\Evalue')) {
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
					$value = trim(preg_replace ($phpIniRe, '',
						preg_replace ($phpEndRe, '', $value)
					));

					return @eval ('return ' .
						$value . ';'
					);
				}
			}

      $booleanTrueValues = preg_split ( '/\s+/',
        'true on yes'
      );
      $booleanFalseValues = preg_split ( '/\s+/',
        'false off no'
      );

      $booleanValues = array_merge (
        $booleanFalseValues,
        $booleanTrueValues
      );

			if (is_numeric($value)) {
				return ( float )($value);
			} elseif (in_array (strtolower($value), $booleanValues)) {
				return in_array (strtolower($value), $booleanTrueValues);
			}

			return $value;
		}
	}}
}

<?php
/* Copyright 2014, Zunautica Initiatives Ltd.
*  Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

	/**
	 * A function to sanitize strings for MySQL entry
	 *
	 * @method string_prepare_mysql(string $string)
	 * @param string $string The string to prepare
	 * @return string The escaped string
	 */
	function string_prepare_mysql($string)
	{
		if(get_magic_quotes_gpc())
			return $string;

		return str_replace(
			array('\\', '"','\''),
			array('\\\\', '\\"', '\\\'',),
			$string);
	}

	/**
	* A function to sanatize a string for XML
	*
	* @method string_prepare_xml(string $string)
	* @param string $string The string to prepare
	* @return string The escaped string
	*/
	function string_prepare_xml($string)
	{
		return str_replace(
			array('&', '<','>','\'','"'),
			array('&amp;', '&lt;', '&gt;', '&apos;', '&quot;'),
			$string);
	}

	/**
	 * A function to clean magic_quotes escape characters from string
	 * 
	 * If magic quotes are not enabled, the string is returned as is
	 *
	 * @method string_clean_escapes(string $string)
	 * @param string $string The string clean the escape characters from
	 * @return string The cleaned string
	 */
	function string_clean_escapes($string)
	{
		if(!get_magic_quotes_gpc())
			return $string;

		return str_replace(
			array('\\\\', '\\"', '\\\'',),
			array('\\', '"','\''),
			$string);
	}

	/**
	 * A function to sanatize a string for transmission in a JSON format
	 * 
	 * @method string_prepare_json(string $string)
	 * @param string $string The string to prepare
	 * @return string The escaped string
	 */
	function string_prepare_json($string)
	{
		return str_replace(
				array("\n", "/", "\b", "\f", "\r", "\t", "\u", "\\'", "\""),
				array("\\n","\\/", "\\b", "\\f", "\\r", "\\t", "\\u", "'", "\\\""),
				$string);
	}

	/**
	 * A function to encode an associative array as a JSON object
	 *
	 * The function takes an associative array where the key is
	 * the name of the property and the value is a value or another
	 * array to parse
	 * 
	 * For example:
	 *
	 * array('id' => 1, 'name' => 'foobar')
	 *
	 * becomes
	 *
	 * { "id": 1, "name": "foobar" }
	 *
	 * Arrays as properties are either recursively parsed by this
	 * function or encode_array depending on if it associative or 
	 * numerically indexed array.
	 *
	 * This method is designed to be recursive and also called by
	 * encode_array.
	 *
	 * You can set an alias for keys if you want to, say, alias
	 * a column name so the table structure is not revealed in
	 * transmissions. The alias is an associative array of the form:
	 *
	 * 'key' => 'alias'
	 *
	 * The function also handles the difference between numeric values
	 * and strings in the encoding.
	 *
	 * This function prepares strings for json
	 *
	 * @method json_encode_object(array $array, array $alias = null)
	 * @param array $array The array of values to encode as an object
	 * @param array $alias An optional array of key aliases
	 * @return string A JSON encoded string
	 */
	function json_encode_object($array, $alias = null)
	{
		ob_start();
		echo "{";
		$sz = sizeof($array);
		foreach($array as $key => $item) {
			if(isset($alias[$key]))
				$key = $alias[$key];

			echo "\"$key\":";
			if(is_array($item) && is_numeric(key($item)))
				echo json_encode_array($item, $alias);
			else
			if(is_array($item))
				echo json_encode_object($item, $alias);
			else {
				if(is_numeric($item ))
					echo $item;
				else
					echo "\"".string_prepare_json($item)."\"";
			}
			if(--$sz > 0)
				echo ",";

		}
		echo "}";
		return ob_get_clean();
	}

	/**
	 * A function to encode a vector array as a JSON array
	 *
	 * The function takes an numerically index vector array
	 * and encoded as a javascript array for JSON to parse
	 * 
	 * For example:
	 *
	 * array("voodoo doll", "saucepan", "mug 'o grog")
	 *
	 * becomes
	 *
	 * [ "voodoo doll", "saucepan", "mug 'o grog" ]
	 *
	 * Arrays as values are either recursively parsed by this
	 * function or encode_object depending on if it associative or 
	 * numerically indexed vector array.
	 *
	 * This method is designed to be recursive and also called by
	 * encode_object.
	 *
	 * You can set an alias for keys in nested arrays if you want 
	 * to, say, alias a column name so the table structure is not 
	 * revealed in transmissions. The alias is an associative array 
	 * of the form:
	 *
	 * 'key' => 'alias'
	 *
	 * The function also handles the difference between numeric values
	 * and strings in the encoding.
	 *
	 * This function prepares strings for json
	 *
	 * @method json_encode_array(array $array, array $alias = null)
	 * @param array $array The array of values to encode as an array
	 * @param array $alias An optional array of key aliases for nested arrays
	 * @return string A JSON encoded string
	 */
	function json_encode_array($array, $alias = null)
	{
		ob_start();
		echo "[";
		$sz = sizeof($array);
		foreach($array as $key => $item) {
			if(isset($alias[$key]))
				$key = $alias[$key];

			if(is_array($item) && is_numeric(key($item)))
				echo json_encode_array($item, $alias);
			else
			if(is_array($item))
				echo json_encode_object($item, $alias);
			else {
				if(is_numeric($item ))
					echo $item;
				else
					echo "\"".string_prepare_json($item)."\"";
			}
			if(--$sz > 0)
				echo ",";

		}
		echo "]";
		return ob_get_clean();
	}

?>

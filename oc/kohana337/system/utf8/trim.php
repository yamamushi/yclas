<?php
/**
 * UTF8::trim
 *
 * @package    Kohana
 * @author     Kohana Team
 * @copyright  (c) Kohana Team
 * @copyright  (c) 2005 Harry Fuecks
 * @license    http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt
 */
function _trim($str, $charlist = NULL)
{
	if ($charlist === NULL)
		return trim($str);

	return UTF8::ltrim(UTF8::rtrim($str, $charlist), $charlist);
}
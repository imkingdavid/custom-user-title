<?php
/**
 *
 * @package customusertitle
 * @copyright (c) 2015 David King (imkingdavid)
 * @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
 *
 */

/**
* DO NOT CHANGE
*/
if (!defined('IN_PHPBB'))
{
	exit;
}

if (empty($lang) || !is_array($lang))
{
	$lang = array();
}

// DEVELOPERS PLEASE NOTE
//
// All language files should use UTF-8 as their encoding and the files must not contain a BOM.
//
// Placeholders can now contain order information, e.g. instead of
// 'Page %s of %s' you can (and should) write 'Page %1$s of %2$s', this allows
// translators to re-order the output of data while ensuring it remains correct
//
// You do not need this where single placeholders are used, e.g. 'Message %d' is fine
// equally where a string contains only two placeholders which are used to wrap text
// in a url you again do not need to specify an order e.g., 'Click %sHERE%s' is fine
// ’ and “”

$lang = array_merge($lang, array(
	'CUSTOM_USER_TITLE_LOCATION' => 'Posizione livello personale',
	'CUSTOM_USER_TITLE_LOCATION_EXPLAIN' => 'Con quest’impostazione, è possibile determinare la posizione del livello personale rispetto a quello predefinito: è possibile mostrarlo sopra, sotto o al posto di esso.',
	'CUSTOM_USER_TITLE_BEFORE_RANK' => 'Prima del livello predefinito',
	'CUSTOM_USER_TITLE_REPLACES_RANK' => 'Rimpiazza il livello predefinito',
	'CUSTOM_USER_TITLE_AFTER_RANK' => 'Dopo il livello predefinito',
));

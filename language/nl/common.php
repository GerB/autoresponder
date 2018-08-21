<?php
/**
 *
 * Autoresponder extension.
 * [Dutch]
 *
 * @copyright (c) 2018, Ger, https://github.com/GerB
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

if (!defined('IN_PHPBB'))
{
	exit;
}

if (empty($lang) || !is_array($lang))
{
	$lang = array();
}
$lang = array_merge($lang, array(
	'AR_MESSAGE_TEMPLATE'				=> 'Sjabloon for automatisch bericht',
	'AR_MESSAGE_TEMPLATE_EXPLAIN'		=> 'Gebruik BBcode (geen HTML). De volgende tokens worden automatisch verwerkt: <br>'
                                        . '{topic_title}: Onderwerptitel <br>'
                                        . '{poster_username}: Gebruikersnaam van degene die het onderwerp opent <br>'
                                        . '{ar_username}: Gebruikersnaam van degene die het antwoord plaatst <br>'
                                        . '{board_url}: Link naar je forum <br>'
                                        . '{topic_url}: Link naar het onderwerp <br>',
	'AR_TITLE'                          => 'Autoresponder instellingen',
	'AR_USER_ID'						=> 'Autoresponder gebruiker id',
	'AR_USER_ID_EXPLAIN'				=> 'De id van de gebruiker op wiens naam de automatische antwoorden geplaatst worden.',
));

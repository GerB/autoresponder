<?php
/**
 *
 * Autoresponder extension.
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
    'AR_MESSAGE_TEMPLATE'				=> 'Template for automated reply',
	'AR_MESSAGE_TEMPLATE_EXPLAIN'		=> 'Use BBcode (no HTML). The following tokens will be processed: <br>'
                                        . '<b>{topic_title}</b>: Topic title <br>'
                                        . '<b>{poster_username}</b>: Username of the user that starts a new topic <br>'
                                        . '<b>{poster_username_color}</b>: User color of the user that starts a new topic <br>'
                                        . '<b>{ar_username}</b>: Username of for the user posting the auto-reply <br>'
                                        . '<b>{board_url}</b>: Link to your board <br>'
                                        . '<b>{topic_url}</b>: Link to the topic',
	'AR_TITLE'                          => 'Autoresponder settings',
	'AR_USER_ID'						=> 'Autoresponder user id',
	'AR_USER_ID_EXPLAIN'				=> 'The id of the user that will be used to post the auto-replies.',
));

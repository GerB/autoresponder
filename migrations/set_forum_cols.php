<?php
/**
 *
 * Autoresponder extension
 *
 * @copyright (c) 2018, Ger, https://github.com/GerB
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

namespace ger\autoresponder\migrations;

class set_forum_cols extends \phpbb\db\migration\migration
{
	static public function depends_on()
	{
		return array('\phpbb\db\migration\data\v320\v320');
	}

	public function update_schema()
	{
        return array(
            'add_columns'        => array(
                $this->table_prefix . 'forums'        => array(
                    'ar_user_id'            => array('UINT', 0, 'after' => 'prune_shadow_next'),
                    'ar_message_template'   => array('MTEXT_UNI', '', 'after' => 'ar_user_id'),
               ),		
            ),	
        );
    }
    
    
    public function revert_schema()
	{
		return array(
			'drop_columns'	=> array(
				$this->table_prefix . 'forums'			=> array(
					'ar_user_id',
					'ar_message_template',
				),
			),
		);
	}
}
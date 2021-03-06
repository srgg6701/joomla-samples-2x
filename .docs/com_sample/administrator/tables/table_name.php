<?php
/**
 * @version     2.1.0
 * @package     com_sample
 * @copyright   Copyright (C) webapps 2012. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      srgg <srgg67@gmail.com> - http://www.facebook.com/srgg67
 */

// No direct access
defined('_JEXEC') or die;

/**
 * customers Table class
 */
class SampleTableDb_table_name extends JTable
{
	/**
	 * Constructor
	 *
	 * @param JDatabase A database connector object
	 */
	public function __construct(&$db)
	{
		parent::__construct('#__db_table_name', 'id', $db);
	}
}
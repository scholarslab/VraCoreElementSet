<?php
/**
 * CsvImport_Import - represents a csv import event
 * 
 * @version $Id$ 
 * @package CsvImport
 * @author CHNM
 * @copyright Center for History and New Media, 2008
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 **/
class VraCoreElementSet_Agent extends Omeka_Record { 
	
		
	/**
    * Gets an array of all of the CsvImport_Import objects from the database
    * 
    * @return array
    */
	public static function getAgents($currentPage)
	{
		$db = get_db();
		$at = $db->getTable('VraCoreElementSet_Agent');
		$agents = $at->findBy(array('id'=>'id'), 10, $currentPage);
        return $agents;
	}
}
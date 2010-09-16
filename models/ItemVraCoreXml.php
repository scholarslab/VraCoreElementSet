<?php 
/**
 * @version $Id$
 * @copyright Scholars' Lab 2010
 * @license http://www.apache.org/licenses/LICENSE-2.0.txt
 * @package VraCoreElementSet
 **/
 
/**
 * @package VraCoreElementSet
 * @author Ethan Gruber
 * @copyright Scholars' Lab 2010
 **/
class ItemVraCoreXml
{
    private $_vraElements = array('Agent', 'Cultural Context', 'Date', 'Description',
    							'Inscription', 'Location', 'Material', 'Measurements', 'Relation',
    							'Rights', 'Source', 'State Edition', 'Style Period', 'Subject', 
    							'Technique', 'Textref', 'Title', 'Worktype');

    public function recordToVraCoreXml($item)
    {
    	$db = get_db();
		$at = $db->getTable('VraCoreElementSet_Agent');
		
		//get VRA Core identifier and write the first one as the work @refid
		$identifiers = item('VRA Core', 'Identifier', array('all'=>true, 'no_escape'=>true));
		$refid = $identifiers[0];
    
        $xml = "\n" . '<work id="' . $item['id'] . '" source="' . settings('site_title') . '" refid="' . $refid . '">';
        // Iterate through the VRA Core.
        foreach ($this->_vraElements as $elementName) {
            if ($text = item('VRA Core', $elementName, array('all'=>true, 'no_escape'=>true))) {
            	$nameParts = explode(' ', $elementName);
            	$newName = strtolower($nameParts[0]) . $nameParts[1];
            	$xml .= "\n" . '<' . $newName . 'Set>';
            	foreach ($text as $k => $v) {
					if (!empty($v)) {
						$xml .= "\n" . '<' . $newName . '>';
						if ($elementName == 'Inscription' || $elementName == 'Rights'){
							$xml .= '<text>' . xml_escape($v) . '</text>';
						}
						elseif ($elementName == 'Location' || $elementName == 'Source' || $elementName == 'Textref' || $elementName == 'State Edition'){
							$xml .= '<name>' . xml_escape($v) . '</name>';
						}
						elseif ($elementName == 'Subject'){
							$xml .= '<term>' . xml_escape($v) . '</term>';
						}
						elseif ($elementName == 'Agent'){
							$agent = $at->find($v);
							$xml .= '<name>' . xml_escape($agent['name']) . '</name>';							
							if ($agent['culture'] != NULL){
								$xml .= '<culture>' . xml_escape($agent['culture']) . '</culture>';
							}
							if ($agent['earliest_date'] != NULL || $agent['latest_date'] != NULL){
								$xml .= '<dates type="life">';
								if ($agent['earliest_date'] != NULL){
									$xml .= '<earliestDate>' . xml_escape($agent['earliest_date']) . '</earliestDate>';
								}
								if ($agent['latest_date'] != NULL){
									$xml .= '<latestDate>' . xml_escape($agent['latest_date']) . '</latestDate>';
								}
								$xml .= '</dates>';
							}
							if ($agent['role'] != NULL){
								$xml .= '<role>' . xml_escape($agent['role']) . '</role>';
							}
						}
						elseif ($elementName == 'Date'){
							$dates = explode(" ", xml_escape($v));
							if ($dates[0] != NULL){
								$xml .= '<earliestDate>' . $dates[0] . '</earliestDate>';
							}
							if ($dates[1] != NULL){
								$xml .= '<latestDate>' . $dates[1] . '</latestDate>';
							}
							
						}
						else{
							$xml .= xml_escape($v);
						}
						$xml .= '</' . $newName . '>';
					}
            	}
            	$xml .= '</' . $newName . 'Set>';
            }
        }
        $xml .= "\n" . '</work>';
        return $xml;        
    }
}
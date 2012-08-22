<?php

/**
 * VRA Core Element Set
 *
 * @copyright  Scholars' Lab 2010
 * @license    http://www.apache.org/licenses/LICENSE-2.0.html
 * @version    $Id:$
 * @package VraCoreElementSet
 * @author Ethan Gruber: ewg4x at virginia dot edu
 **/

if (!defined('VRA_CORE_ELEMENT_SET_VERSION')) {
    define('VRA_CORE_ELEMENT_SET_VERSION', get_plugin_ini('VraElementSet', 'version'));
}

if (!defined('VRA_CORE_ELEMENT_SET_PLUGIN_DIR')) {
    define('VRA_CORE_ELEMENT_SET_PLUGIN_DIR', dirname(__FILE__));
}

require_once VRA_CORE_ELEMENT_SET_PLUGIN_DIR . 'VraElementSet.php';

new VraElementSet();

// TODO: refactor to VraElementSet

/*
 * Local variables:
 * tab-width: 4
 * c-basic-offset: 4
 * c-hanging-comment-ender-p: nil
 * End:
 */

add_plugin_hook('after_insert_item', 'vra_core_after_insert_item');
// add_plugin_hook('initialize', 'vra_core_initialize');
//add_plugin_hook('define_acl', 'vra_core_define_acl');
//add_plugin_hook('admin_theme_header', 'vra_core_admin_header');
//add_filter('admin_navigation_main', 'vra_core_admin_navigation');
//add_filter('define_action_contexts', 'vra_core_action_context');
//add_filter('define_response_contexts', 'vra_core_response_context');



/**
 * Execute VRA Core to Dublin Core metadata crosswalk
 */
function vra_core_after_insert_item($item){
	$db = get_db();
	
	$vraCoreElements = $item->getElementsBySetName('VRA Core');
	//crosswalk mapping from http://www.getty.edu/research/conducting_research/standards/intrometadata/crosswalks.html		
	$crosswalk = array('Worktype'=>'Type', 'Subject'=>'Subject', 'Title'=>'Title', 'Agent'=>'Creator',
						'Date'=>'Date', 'Location'=>'Coverage', 'Style Period'=>'Subject',
						'Measurements'=>'Format', 'Technique'=>'Format', 'Inscription'=>'Description',
						'Description'=>'Description', 'Relation'=>'Relation', 'Rights'=>'Rights', 'Identifier'=>'Identifier');
	
	
	$hasVraElements = has_vra_core_element_texts($item);
	
	//only execute crosswalk of VRA Core fields exist for an item
	if ($hasVraElements == true){	
		foreach ($crosswalk as $vraField=>$dcField){
			//get elements for current DC field to avoid inserting duplicates
			$dcElement = $item->getElementByNameAndSetName($dcField, 'Dublin Core');					
			$dcElementTexts = $item->getTextsByElement($dcElement);
			$texts = array();
			
			//remove DC element texts to avoid duplication on VRA Core changes. this is experimental and as a result prohibits separate DC entry
			foreach ($dcElementTexts as $dcElementText){
				$dcElementText->delete();			
			}
			
			$vraElementTexts = $item->getElementTextsByElementNameAndSetName($vraField, 'VRA Core');
			
			foreach ($vraElementTexts as $vraElementText){
				if ($vraField == 'Agent'){
					$value = preg_replace('/\s\s+/', ' ', $db->getTable('VraCoreElementSet_Agent')->find($vraElementText['text'])->name);
				} else {
					$value = preg_replace('/\s\s+/', ' ', $vraElementText['text']);
				}	
				$db->insert('element_texts', array('record_id'=>$item->id, 'record_type_id'=>2, 'element_id'=>$dcElement->id, 'text'=>$value));
			}	
		}
	}
}



function vra_core_define_acl($acl)
{
    $acl->loadResourceList(array('VraCoreElementSet_Agents' => array('index', 'status')));
}


/*********************************************
 * Public functions
 *********************************************/
function render_vra_core_xml($item){
	return 'test';
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

//test to see if an item has any VRA Core element texts (used in after_insert_item plugin hook as well as a public function
function has_vra_core_element_texts($item){
	$vraCoreElements = $item->getElementsBySetName('VRA Core');
	//test for existing VRA Core fields
	foreach ($vraCoreElements as $vraCoreElement){
		$texts = $item->getElementTextsByElementNameAndSetName($vraCoreElement->name, 'VRA Core');
		if ($texts[0]->text != NULL && $texts[0]->text != ''){
			return true;
		}
	}
	return false;
}
/***
 * Display the VRA Core metadata in the same fashion that DC metadata is displayed, 
 * except showing Agent information from the table correctly
 ***/
function vra_core_show_item_metadata($item){
	$vraCoreElements = $item->getElementsBySetName('VRA Core');
	$showEmptyElements = get_option('show_empty_elements');
	foreach ($vraCoreElements as $vraCoreElement){
		$elementTexts = $item->getTextsByElement($vraCoreElement);
		if ($showEmptyElements == false){
			if (isset($elementTexts[0])){				
				$html .= '<div id="vra-core-' . strtolower($vraCoreElement->name) . '" class="element">';
				$html .= '<h3>' . $vraCoreElement->name . '</h3>';
				$html .= render_vra_core_element_text($elementTexts, $vraCoreElement->name);
				$html .= '</div>';
			}
		} else if ($showEmptyElements == true){
			$html .= '<div id="vra-core-' . strtolower($vraCoreElement->name) . '" class="element">';
			$html .= '<h3>' . $vraCoreElement->name . '</h3>';
			$html .= isset($elementTexts[0]) ? render_vra_core_element_text($elementTexts, $vraCoreElement->name) : '<div class="element-text-empty">[no text]</div>';	
			$html .= '</div>';
		}		
	}
	return $html;
}

function render_vra_core_element_text($elementTexts, $elementName){
	$db = get_db();
	foreach ($elementTexts as $elementText){
		if ($elementName == 'Agent'){
			$agent = $db->getTable('VraCoreElementSet_Agent')->find($elementText->text);
			$html .= '<div class="element-text"><ul><li><b>' . $agent->name . '</b></li>';
			$html .= !empty($agent->culture) ? '<li>Culture: ' . $agent->culture . '</li>' : '';
			$html .= !empty($agent->role) ? '<li>Role: ' . $agent->role . '</li>' : '';
			$html .= !empty($agent->earliest_date) ? '<li>Earliest Date: ' . $agent->earliest_date . '</li>' : '';
			$html .= !empty($agent->latest_date) ? '<li>Latest Date: ' . $agent->latest_date . '</li>': '';
			$html .= '</ul></div>';
		} else{
			$html .= '<div class="element-text">' . $elementText->text . '</div>';
		}	
	}
	return $html;
}

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

add_plugin_hook('install', 'vra_core_install');
add_plugin_hook('uninstall', 'vra_core_uninstall');
add_plugin_hook('after_save_item', 'vra_core_after_save_item');
add_plugin_hook('initialize', 'vra_core_initialize');
add_plugin_hook('define_acl', 'vra_core_define_acl');
add_plugin_hook('admin_theme_header', 'vra_core_admin_header');
add_filter('admin_navigation_main', 'vra_core_admin_navigation');
add_filter('define_action_contexts', 'vra_core_action_context');
add_filter('define_response_contexts', 'vra_core_response_context');


function vra_core_install()
{
	$db = get_db();
    
    // create csv imports table
    $db->exec("CREATE TABLE IF NOT EXISTS `{$db->prefix}vra_core_element_set_agents` (
       `id` int(10) unsigned NOT NULL auto_increment,
       `name` tinytext collate utf8_unicode_ci NOT NULL,
       `culture` tinytext collate utf8_unicode_ci,
       `earliest_date` tinytext collate utf8_unicode_ci,
       `latest_date` tinytext collate utf8_unicode_ci,
       `role` tinytext collate utf8_unicode_ci,
       PRIMARY KEY  (`id`)
       ) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;");

	$elementSetMetadata = array(
		'name'        => 'VRA Core', 
    	'description' => 'VRA Core standard for artistic pieces and cultural heritage artifacts'
	);
	$elements = array(
		array(
	    	'name'           => 'Title',
	        'description'    => 'The title or identifying phrase given to a Work or an Image.',
	        'record_type_id' => 2,
	        'data_type_id'   => 2
	    ),
	    array(
	    	'name'           => 'Agent',
	        'description'    => 'The names, appellations, or other identifiers assigned to an individual, group, or corporate body that has contributed to the design, creation, production, manufacture, or alteration of the work or image.',
	        'record_type_id' => 2,
	        'data_type_id'   => 4
	    ),
	    array(
			'name'           => 'Cultural Context',
	        'description'    => 'The name of the culture, people (ethnonym), or adjectival form of a country name fromwhich a Work, Collection, or Image originates, or the cultural context with which the Work, Collection, or Image has been associated.',
	        'record_type_id' => 2,
	        'data_type_id'   => 2
	    ),
		array(
	    	'name'           => 'Date',
	        'description'    => 'Date or range of dates associated with the creation, design, production, presentation, performance, construction, or alteration, etc. of the work or image. Dates may be expressed as free text or numerical.  In format yyyy-mm-dd yyyy-mm-dd.',
	        'record_type_id' => 2,
	        'data_type_id'   => 3
	    ),
		array(
	    	'name'           => 'Description',
	        'description'    => 'A free-text note about the Work, Collection, or Image, including comments, description, or interpretation, that gives additional information not recorded in other categories.',
	        'record_type_id' => 2,
	        'data_type_id'   => 1
	    ),
		array(
	    	'name'           => 'Inscription',
	        'description'    => 'All marks or written words added to the object at the time of production or in its subsequent history, including signatures, dates, dedications, texts, and colophons, as well as marks, such as the stamps of silversmiths, publishers, or printers. ',
	        'record_type_id' => 2,
	        'data_type_id'   => 2
	    ),
		array(
	    	'name'           => 'Location',
	        'description'    => 'The geographic location and/or name of the repository, building, site, or other entity whose boundaries include the Work or Image.',
	        'record_type_id' => 2,
	        'data_type_id'   => 2
	    ),
	    array(
	    	'name'           => 'Material',
	        'description'    => 'The substance of which a work or an image is composed.',
	        'record_type_id' => 2,
	        'data_type_id'   => 2
	    ),
	    array(
	    	'name'           => 'Measurements',
	        'description'    => 'The physical size, shape, scale, dimensions, or format of the Work or Image. Dimensions may include such measurements as volume, weight, area or running time.',
	        'record_type_id' => 2,
	        'data_type_id'   => 2
	    ),
	    array(
	    	'name'           => 'Relation',
	        'description'    => 'Terms or phrases describing the identity of the related work and the relationship between the work being cataloged and the related work or image.',
	        'record_type_id' => 2,
	        'data_type_id'   => 2
	    ),
	    array(
	    	'name'           => 'Rights',
	        'description'    => 'Information about the copyright status and the rights holder for a work, collection, or image',
	        'record_type_id' => 2,
	        'data_type_id'   => 2
	    ),
	    array(
	    	'name'           => 'Source',
	        'description'    => 'A reference to the source of the information recorded about the work or the image.',
	        'record_type_id' => 2,
	        'data_type_id'   => 2
	    ),
	    array(
	    	'name'           => 'State Edition',
	        'description'    => 'The identifying number and/or name assigned to the state or edition of a work that exists in more than one form and the placement of that work in the context of prior or later issuances of multiples of the same work.',
	        'record_type_id' => 2,
	        'data_type_id'   => 2
	    ),
	    array(
	    	'name'           => 'Style Period',
	        'description'    => 'A defined style, historical period, group, school, dynasty, movement, etc. whose characteristics are represented in the Work or Image.',
	        'record_type_id' => 2,
	        'data_type_id'   => 2
	    ),
	    array(
	    	'name'           => 'Subject',
	        'description'    => 'Terms or phrases that describe, identify, or interpret the Work or Image and what it depicts or expresses. These may include generic terms that describe the work and the elements that it comprises, terms that identify particular people, geographic places, narrative and iconographic themes, or terms that refer to broader concepts or interpretations.',
	        'record_type_id' => 2,
	        'data_type_id'   => 2
	    ),
	    array(
	    	'name'           => 'Technique',
	        'description'    => 'The production or manufacturing processes, techniques, and methods incorporated in the fabrication or alteration of the work or image.',
	        'record_type_id' => 2,
	        'data_type_id'   => 2
	    ),
	    array(
	    	'name'           => 'Textref',
	        'description'    => 'Contains the name of a related textual reference and any type of unique identifier that text assigns to a Work or Collection that is independent of any repository.',
	        'record_type_id' => 2,
	        'data_type_id'   => 2
	    ),
		array(
	    	'name'           => 'Worktype',
	        'description'    => 'Identifies the specific type of WORK, COLLECTION, or IMAGE being described in the record.',
	        'record_type_id' => 2,
	        'data_type_id'   => 2
	    ),
	    array(
	    	'name'           => 'Identifier',
	        'description'    => 'Equivalent of the refid attribute that holds the local unique identifier for the work described by the element set',
	        'record_type_id' => 2,
	        'data_type_id'   => 2
	    ),
	);
	insert_element_set($elementSetMetadata, $elements);
}

/**
 * Uninstall the plugin.
 * 
 * @return void
 */
function vra_core_uninstall()
{
    $db = get_db();
    $sql = "DROP TABLE IF EXISTS `{$db->prefix}vra_core_element_set_agents`";
    $db->query($sql);
    
	$elementSet = get_db()->getTable('ElementSet')->findByName('VRA Core');
	$elementSet->delete();
}	
/**
 * Execute VRA Core to Dublin Core metadata crosswalk
 */
function vra_core_after_save_item($item){
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


function vra_core_initialize()
{
	$front = Zend_Controller_Front::getInstance();
	$front->registerPlugin(new VraCoreElementSet_Controller_Plugin_SelectFilter);
}

/**
 * Add the admin navigation for the plugin.
 * 
 * @return array
 */
function vra_core_admin_navigation($tabs)
{
    if (get_acl()->checkUserPermission('VraCoreElementSet_Agents', 'index')) {
        $tabs['VRA Core Agents'] = uri('vra-core-element-set/agents/browse/');        
    }
    return $tabs;
}

function vra_core_define_acl($acl)
{
    $acl->loadResourceList(array('VraCoreElementSet_Agents' => array('index', 'status')));
}

function vra_core_admin_header($request)
{
	if ($request->getModuleName() == 'vra-core-element-set') {
		echo '<link rel="stylesheet" href="' . html_escape(css('vra_core_element_set_main')) . '" />';
		//echo js('generic_xml_import_main');
    }
}
function vra_core_action_context($context, $controller)
{
    if ($controller instanceof ItemsController) {
		$context['show'][] = 'vra-core-xml';
    } 
    return $context;
}

function vra_core_response_context($context)
{
    $context['vra-core-xml'] = array('suffix'  => 'vra-core', 
                            'headers' => array('Content-Type' => 'text/xml')); 
    return $context;
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

//test to see if an item has any VRA Core element texts (used in after_save_item plugin hook as well as a public function
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
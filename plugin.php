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

add_plugin_hook('install', 'VraCoreElementSetPlugin::install');
add_plugin_hook('uninstall', 'VraCoreElementSetPlugin::uninstall');
add_plugin_hook('initialize', 'VraCoreElementSetPlugin::initialize');
add_plugin_hook('define_acl', 'VraCoreElementSetPlugin::define_acl');
add_plugin_hook('admin_theme_header', 'VraCoreElementSetPlugin::admin_header');
add_filter('admin_navigation_main', 'VraCoreElementSetPlugin::admin_navigation');

class VraCoreElementSetPlugin
{
	public static function install()
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
		        'description'    => 'Date or range of dates associated with the creation, design, production, presentation, performance, construction, or alteration, etc. of the work or image. Dates may be expressed as free text or numerical.',
		        'record_type_id' => 2,
		        'data_type_id'   => 2
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
		    	'name'           => 'Text Reference',
		        'description'    => 'Contains the name of a related textual reference and any type of unique identifier that text assigns to a Work or Collection that is independent of any repository.',
		        'record_type_id' => 2,
		        'data_type_id'   => 2
		    ),
			array(
		    	'name'           => 'Worktype',
		        'description'    => 'Identifies the specific type of WORK, COLLECTION, or IMAGE being described in the record.',
		        'record_type_id' => 2,
		        'data_type_id'   => 2
		    )
		);
		insert_element_set($elementSetMetadata, $elements);
	}
	
	/**
	 * Uninstall the plugin.
	 * 
	 * @return void
	 */
	public static function uninstall()
	{
	    $db = get_db();
	    $sql = "DROP TABLE IF EXISTS `{$db->prefix}vra_core_element_set_agents`";
	    $db->query($sql);
	    
		$elementSet = get_db()->getTable('ElementSet')->findByName('VRA Core');
		$elementSet->delete();
	}
	
    public static function initialize()
    {
        $front = Zend_Controller_Front::getInstance();
        $front->registerPlugin(new VraCoreElementSet_Controller_Plugin_SelectFilter);
    }
	
	/**
	 * Add the admin navigation for the plugin.
	 * 
	 * @return array
	 */
	public static function admin_navigation($tabs)
	{
	    if (get_acl()->checkUserPermission('VraCoreElementSet_Agents', 'index')) {
	        $tabs['VRA Core Agents'] = uri('vra-core-element-set/agents/browse/');        
	    }
	    return $tabs;
	}
	
	public static function define_acl($acl)
	{
	    $acl->loadResourceList(array('VraCoreElementSet_Agents' => array('index', 'status')));
	}
	
	public static function admin_header($request)
	{
		if ($request->getModuleName() == 'vra-core-element-set') {
			echo '<link rel="stylesheet" href="' . html_escape(css('vra_core_element_set_main')) . '" />';
			//echo js('generic_xml_import_main');
	    }
	}
}
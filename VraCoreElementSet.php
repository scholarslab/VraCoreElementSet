<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
class VraCoreElementSet {
    private static $_hooks = array(
        'install',
        'uninstall',
        'after_insert_item',
        'upgrade',
        // 'define_acl',
        'admin_theme_header',
        'admin_append_to_plugin_uninstall_message'
    );

    private static $_filters = array(
        'admin_navigation_main',
        'define_action_contexts',
        'define_response_contexts'
    );

    private static $_vraSchema = 'settings/vra.xsd';
    private static $_db;

    /**
     * Constructor
     *
     * @return void
     */
    public function __construct()
    {
        $this->_db = get_db();
        self::addHooksAndFilters();
    }

    /**
     * Variablize the hooks and filter names
     *
     * @return void
     */
    public function addHooksAndFilters()
    {
        foreach (self::$_hooks as $hookName) {
            $functionName = Inflector::variablize($hookName);
            add_plugin_hook($hookName, array($this, $functionName));
        }

        foreach (self::$_filters as $filterName) {
            $functionName = Inflector::variablize($filterName);
            add_filter($filterName, array($this, $functionName));
        }
    }

    /**
     * Install the plugin, creating a table of agents
     *
     * @return void
     */
    public function install()
    {
        $sql = <<<SQL
    CREATE TABLE IF NOT EXISTS `{$db->prefix}vra_core_element_set_agents` (
       `id` int(10) unsigned NOT NULL auto_increment,
       `name` tinytext collate utf8_unicode_ci NOT NULL,
       `culture` tinytext collate utf8_unicode_ci,
       `earliest_date` tinytext collate utf8_unicode_ci,
       `latest_date` tinytext collate utf8_unicode_ci,
       `role` tinytext collate utf8_unicode_ci,
       PRIMARY KEY  (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
SQL;
        $this->_db->exec($sql);

        self::insertElements();
    }

    /**
     * Uninstall the plugin, cleaning up the element set agents table an 
     * removing the element set from the registry
     *
     * @return void
     */
    public function uninstall()
    {

        $sql = "DROP TABLE IF EXISTS `{$db->prefix}vra_core_element_set_agents`";
        $this->_db->exec($sql);

        $elementSet = $this->_db->getTable('ElementSet')->findByName('VRA Core');
        $elementSet->delete();

        delete_option('vracoreelementset');

    }

    /**
     * Upgrade the plugin.
     *
     * @param string $oldVersion Current version of the plugin
     * @param string $newVersion Targer plugin version number
     *
     * @return void
     */
    public function upgrade($oldVersion, $newVersion)
    {

    }

    /**
     * Fired after an item has been inserted
     *
     * @param OmekaItem $item Omeka item
     *
     * @return void
     */
    public function afterInsertItem($item)
    {

    }

    /**
     * Define the ACL
     *
     * @return void
     */
    public function defineAcl()
    {

    }

    /**
     * Queue the CSS file
     *
     * @param Request $request Admin request
     *
     * @return void
     */
    public function adminThemeHeader($request)
    {
        queue_css('vra_core_element_set_main');
    }


    /**
     * VraCoreElementSet hook to notify users on uninstall that their data is
     * going bye-bye.
     *
     * @return void
     */
    public function adminAppendToPluginUninstallMessage()
    {
        $string = __('<strong>Warning</strong>: Uninstalling the VRACoreElementSet plugin will remove all VRACore metadata');

        echo "<p>$string</p>";
    }

    /**
     * Add VRA Core Agents listing to the navigation bar
     *
     * @param array $tabs Admin navigation tabs
     *
     * @return array Admin tabs
     */
    public function adminNavigationMain($tabs)
    {
        $tabs['VRA Core Agents'] = uri('vra-core-element-set/agents/browse');

        return $tabs;
    }

    /**
     * Add vra-core contect to the 'item' actions for the
     * ItemsController
     *
     * @param Context         $context    Omeka Context object
     * @param OmekaController $controller Omeka controller
     *
     * @return Context New Context
     */
    public function defineActionContexts($context, $controller)
    {
        if ($controller instanceof ItemsController) {
            $context['items'][] = 'vra-core-xml';
        }

        return $context;
    }

    /**
     * Adds vra-core context to response contexts to generate XML version
     * of the metadata
     *
     * @param Context $context context
     *
     * @return Context vra-core response context
     */
    public function defineResponseContexts($context)
    {
        $context['vra-core-xml'] = array(
            'suffix' => 'vra-core',
            'headers' => array(
                'Content-Type' => 'text/xml'
            )
        );

        return $context;
    }

    /**
     * Adds VRA element to Omeka
     *
     * @return void
     */
    protected function insertElements()
    {
        $elementSetMetadata = array(
            'name' => 'VRA Core',
            'description' => 'VRA Core Element set based on VRA Core 4.0 guidelines and the unrestricted schema.'
        );

        $elements = vraToElementSetArray($_vraSchema);

        insert_element_set($elementSetMetadata, $elements);
    }

    /**
     * Parse the VRACoreElementSet schema to generate array for Omeka Element set
     *
     * @param string $schemaLocation Location of the schema
     *
     * @return array Array for Omeka Element set
     */
    protected function vraToElementSetArray($schemaLocation)
    {
        $elementArray = array();

        $xsd = simplexml_load_file($schemaLocation);

        foreach ($xsd->xpath('//xsd:element') as $element) {

            $atts = $element->attributes();

            $name = (string)$atts->name;
            $pointer = (string)$atts->type;

            $docXPath = "//*[@name='$pointer']/xsd:annotation/xsd:documentation";

            $docDesc = $xsd->xpath($docXPath);
            $elementDescription = (string)$docDesc[0];

            $vra_element = array(
                $name,
                trim($elementDescription),
                2,
                2
            );

            array_push($elementArray, $vra_element);

        }

        return $elementArray;
    }
}

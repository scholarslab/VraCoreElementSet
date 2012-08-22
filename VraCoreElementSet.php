<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
class VraCoreElementSet {
    private static $_hooks = array(
        'install',
        'uninstall',
        'after_insert_item',
        'define_acl',
        'admin_theme_header'
    );

    private static $_filters = array(
        'admin_navigation_main',
        'define_action_contexts',
        'define_response_contexts'
    );

    public function __construct()
    {
        $this->_db = get_db();
        self::addHooksAndFilters();
    }

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

    public function uninstall()
    {

        $sql = "DROP TABLE IF EXISTS `{$db->prefix}vra_core_element_set_agents`";
        $this->_db->exec($sql);

        $elementSet = $this->_db->getTable('ElementSet')->findByName('VRA Core');
        $elementSet->delete();

    }

    public function afterInsertItem($item)
    {

    }

    public function defineAcl()
    {

    }

    public function adminNavigationMain()
    {

    }

    public function defineActionContexts()
    {

    }

    public function defineResponseContexts()
    {

    }

    protected function insertElements()
    {
        //TODO: talk about the best way to populate this data
    }
}

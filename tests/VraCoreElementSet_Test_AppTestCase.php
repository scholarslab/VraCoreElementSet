<?php

class VraElementSet_Test_AppTestCase extends Omeka_Test_AppTestCase
{
    private $_dbHelper;


    public function setUpPlugin()
    {
        parent::setUp();

        $this->user = $this->db->getTable('User')->find(1);
        $this->_authenticateUser($this->user);

        $pluginBroker = get_plugin_broker();
        $this->addHooksAndFilters($pluginBroker, 'VraElementSet');
        $pluginHelper = new Omeka_Test_Helper_Plugin;
        $pluginHelper->setUp('VraElementSet');

        $this->_dbHelper = Omeka_Test_Helper_Db::factory($this->core);

    }

    public function addHooksAndFilters($pluginBroker, $pluginName)
    {
        $pluginBroker->setCurrentPluginDirName($pluginName);
        new VraElementSet;
    }


    public function tearDown()
    {
        parent::tearDown();
    }

    public function item($title = null, $subject = null)
    {
        $item = new Item();
        $item->save();

        return $item;
    }
}

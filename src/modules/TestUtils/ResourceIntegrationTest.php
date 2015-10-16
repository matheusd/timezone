<?php

namespace MDTimezone\TestUtils;

abstract class ResourceIntegrationTest extends \PHPUnit_Extensions_Database_TestCase {

    protected $di;

    private static $pdo;
    private $conn;

    protected static function getPdo()
    {
        return self::$pdo;
    }

    protected function getSetUpOperation()
    {
        //using postgres it's useful to set cascade as true
        return \PHPUnit_Extensions_Database_Operation_Factory::CLEAN_INSERT(true);
    }

    public function setUp()
    {
        $this->di = new \Pimple\Container();
        $provider = new \WebAppDIProvider();
        $provider->register($this->di);
        $this->di['dbConfigName'] = 'test';
        if (self::$pdo) {
            $this->di['pdo'] = self::$pdo;
        }
        $this->di['requestParameters'] = [];
        $this->di['session'] = new MockSessionStorage('');
        $this->di['logger'] = new MockLogger();

        parent::setUp();
    }

    protected function modifySessionData($data) {
        $this->di['session']->setSessionData($data);
    }

    protected function modifyRequestParameters($newParameters) {
        $this->di['requestParameters'] = $newParameters;
    }

    protected function prepareRequest($method, $path, $parsedBodyData) {
        $this->di['request'] = new MockRequest($method, $path, $parsedBodyData);
    }

    protected final function getConnection()
    {
        if ($this->conn == null) {
            if (self::$pdo == null) {
                $entityManager = $this->di['entityManager'];
                $pdo = $entityManager->getConnection()->getWrappedConnection();                
                self::$pdo = $pdo;
            }
            $this->conn = $this->createDefaultDBConnection(self::$pdo, ':memory:');
        }
        return $this->conn;
    }

    protected function getDataSetData()
    {
        return [];
    }

    protected function getDataSet() {
        return new \PHPUnit_Extensions_Database_DataSet_ArrayDataSet($this->getDataSetData());
    }

}
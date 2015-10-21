<?php

class EditTimezoneIntegrationTest extends \MDTimezone\TestUtils\ResourceIntegrationTest {

    protected function getDataSetData() {
        return [
            'users' => [
                ['id' => '10', 'name' => 'u1', 'email' => 'u1@t.c', 'role' => 0, 'password' => '123'],
                ['id' => '11', 'name' => 'u2', 'email' => 'u2@t.c', 'role' => 1, 'password' => '123'],
                ['id' => '13', 'name' => 'u3', 'email' => 'u3@t.c', 'role' => 999, 'password' => '123'],
                ['id' => '14', 'name' => 'u4', 'email' => 'u4@t.c', 'role' => 0, 'password' => '123'],
            ],
            'timezones' => [
                ['id' => 10, 'user_id' => 10, 'name' => 'America/Sao_Paulo'],
                ['id' => 11, 'user_id' => 10, 'name' => 'America/New_York'],
                ['id' => 12, 'user_id' => 11, 'name' => 'America/New_York'],
                ['id' => 13, 'user_id' => 14, 'name' => 'America/New_York'],
                ['id' => 14, 'user_id' => 11, 'name' => 'America/New_York'],
            ]
        ];
    }

    public function testNotLoggedInCantModifyTimezones() {
        $this->prepareRequest('POST', '/timezone/10', ['name' => 'America/Antigua']);
        $response = $this->di['response'];
        $this->assertEquals(403, $response->getStatusCode());
    }

    public function testCanModifyOwnTimezones() {
        $this->modifySessionData(['userId' => 10]);
        $this->prepareRequest('POST', '/timezone/10', ['name' => 'America/Antigua']);
        $response = $this->di['response'];
        $this->assertEquals(200, $response->getStatusCode());
        $tzs = $this->di['model/timezones']->listUserTimezones(10);
        $this->assertEquals("America/Antigua", $tzs[0]->getName());
    }

    public function testCanModifyInexistentTimezones() {
        $this->modifySessionData(['userId' => 10]);
        $this->prepareRequest('POST', '/timezone/20', ['name' => 'America/Antigua']);
        $response = $this->di['response'];
        $this->assertEquals(404, $response->getStatusCode());
    }

    public function testCannotModifyOtherUserTimezones() {
        $this->modifySessionData(['userId' => 10]);
        $this->prepareRequest('POST', '/timezone/13', ['name' => 'America/Antigua']);
        $response = $this->di['response'];
        $this->assertEquals(403, $response->getStatusCode());
    }

    public function testManagerCanModifyOtherUserTimezones() {
        $this->modifySessionData(['userId' => 11]);
        $this->prepareRequest('POST', '/timezone/13', ['name' => 'America/Antigua']);
        $response = $this->di['response'];
        $this->assertEquals(200, $response->getStatusCode());
        $tzs = $this->di['model/timezones']->listUserTimezones(14);
        $this->assertEquals("America/Antigua", $tzs[0]->getName());
    }

    public function testAdminCanModifyManagerTimezones() {
        $this->modifySessionData(['userId' => 13]);
        $this->prepareRequest('POST', '/timezone/12', ['name' => 'America/Antigua']);
        $response = $this->di['response'];
        $this->assertEquals(200, $response->getStatusCode());
        $tzs = $this->di['model/timezones']->listUserTimezones(11);
        $this->assertEquals("America/Antigua", $tzs[0]->getName());
    }

    public function testAdminCanDeleteTimezone() {
        $this->modifySessionData(['userId' => 13]);
        $this->prepareRequest('DELETE', '/timezone/12', []);
        $response = $this->di['response'];
        $this->assertEquals(200, $response->getStatusCode());
        $tzs = $this->di['model/timezones']->listUserTimezones(11);
        $this->assertCount(1, $tzs);
    }
}
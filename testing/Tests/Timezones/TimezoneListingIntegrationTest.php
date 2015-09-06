<?php

class TimezoneListingIntegrationTest extends \ToptalTimezone\TestUtils\ResourceIntegrationTest {

    protected function getDataSetData() {
        return [
            'users' => [
                ['id' => '10', 'name' => 'u1', 'email' => 'u1@t.c', 'role' => 0, 'password' => '123'],
                ['id' => '11', 'name' => 'u2', 'email' => 'u2@t.c', 'role' => 1, 'password' => '123'],
            ],
            'timezones' => [
                ['id' => 10, 'user_id' => 10, 'name' => 'America/Sao_Paulo'],
                ['id' => 11, 'user_id' => 10, 'name' => 'America/New_York'],
                ['id' => 12, 'user_id' => 11, 'name' => 'America/New_York'],
            ]
        ];
    }

    public function testNotLoggedInCantSeeTimezones() {
        $this->prepareRequest('GET', '/timezones', []);
        $response = $this->di['response'];
        $this->assertEquals(403, $response->getStatusCode(), $response->getBody());
    }

    public function testCanSeeOwnTimezones() {
        $this->modifySessionData(['userId' => 10]);
        $this->prepareRequest('GET', '/timezones', []);
        $response = $this->di['response'];
        $this->assertEquals(200, $response->getStatusCode());
        $respData = json_decode($response->getBody());
        $this->assertCount(2, $respData->timezones);
    }

    public function testCanCreateTimezone() {
        $this->modifySessionData(['userId' => 10]);
        $this->prepareRequest('POST', '/timezones', ['name' => 'America/Antigua']);
        $response = $this->di['response'];
        $this->assertEquals(200, $response->getStatusCode(), $response->getBody());
        $tzs = $this->di['model/timezones']->listUserTimezones(10);
        $this->assertCount(3, $tzs);
        $this->assertEquals("America/Antigua", $tzs[count($tzs)-1]->getName());
    }

}
<?php

class UserTimezoneListingIntegrationTest extends \ToptalTimezone\TestUtils\ResourceIntegrationTest {

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

    public function testNotLoggedInCantSeeTimezones() {
        $this->prepareRequest('GET', '/timezones/fromUser/10', []);
        $response = $this->di['response'];
        $this->assertEquals(403, $response->getStatusCode());
    }
   
    public function testUserCantSeeOthersTimezone() {
        $this->modifySessionData(['userId' => 11]);
        $this->prepareRequest('GET', '/timezones/fromUser/14', []);
        $response = $this->di['response'];
        $this->assertEquals(403, $response->getStatusCode());
    }

    public function testManagerCantSeeOthersTimezone() {
        $this->modifySessionData(['userId' => 11]);
        $this->prepareRequest('GET', '/timezones/fromUser/14', []);
        $response = $this->di['response'];
        $this->assertEquals(403, $response->getStatusCode());
    }

    public function testAdminCanSeeOthersTimezone() {
        $this->modifySessionData(['userId' => 13]);
        $this->prepareRequest('GET', '/timezones/fromUser/14', []);
        $response = $this->di['response'];
        $this->assertEquals(200, $response->getStatusCode());
        $respData = json_decode($response->getBody());
        $this->assertCount(1, $respData->timezones);
    }

    public function testAdminCanAddOthersTimezone() {
        $this->modifySessionData(['userId' => 13]);
        $this->prepareRequest('POST', '/timezones/fromUser/14', ['name' => 'America/Antigua']);
        $response = $this->di['response'];
        $this->assertEquals(200, $response->getStatusCode());
        $tzs = $this->di['model/timezones']->listUserTimezones(14);
        $this->assertCount(2, $tzs);
        $this->assertEquals("America/Antigua", $tzs[1]->getName());
    }

    public function testManagerCannotAddOthersTimezone() {
        $this->modifySessionData(['userId' => 11]);
        $this->prepareRequest('POST', '/timezones/fromUser/14', ['name' => 'America/Antigua']);
        $response = $this->di['response'];
        $this->assertEquals(403, $response->getStatusCode());
        $tzs = $this->di['model/timezones']->listUserTimezones(14);
        $this->assertCount(1, $tzs);
    }

}


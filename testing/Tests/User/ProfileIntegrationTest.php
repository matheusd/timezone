<?php

class ProfileIntegrationTest extends \ToptalTimezone\TestUtils\ResourceIntegrationTest {

    protected function getDataSetData() {
        return [
            'users' => [
                ['id' => '10', 'name' => 'u1', 'email' => 'u1@t.c', 'role' => 0, 'password' => '123'],
                ['id' => '11', 'name' => 'u1', 'email' => 'u2@t.c', 'role' => 0, 'password' => '123'],
            ]
        ];
    }

    public function testNotLoggedInCantSeeProfile() {
        $this->prepareRequest('GET', '/user/profile', []);
        $response = $this->di['response'];
        $this->assertEquals(403, $response->getStatusCode());
    }

    public function testLoggedInCanSeeProfile() {
        $this->modifySessionData(['userId' => 10]);
        $this->prepareRequest('GET', '/user/profile', []);
        $response = $this->di['response'];
        $this->assertEquals(200, $response->getStatusCode(), $response->getBody());
    }

    public function testUnloggedCantPostProfile() {
        $this->prepareRequest('POST', '/user/profile', ['name' => 'test']);
        $response = $this->di['response'];
        $this->assertEquals(403, $response->getStatusCode(), $response->getBody());
    }

    public function testLoggedCanPostProfile() {
        $this->modifySessionData(['userId' => 10]);
        $this->prepareRequest('POST', '/user/profile', ['name' => 'test']);
        $response = $this->di['response'];
        $this->assertEquals(200, $response->getStatusCode(), $response->getBody());
        $this->assertEquals('test', $this->di['model/auth']->currentUser()->getName());
    }

    public function testCantChangeEmailToExisting() {
        $this->modifySessionData(['userId' => 10]);
        $this->prepareRequest('POST', '/user/profile', ['email' => 'u2@t.c']);
        $response = $this->di['response'];
        $this->assertEquals(400, $response->getStatusCode(), $response->getBody());
    }

    public function testCantChangeSelfRole() {
        $this->modifySessionData(['userId' => 10]);
        $this->prepareRequest('POST', '/user/profile', ['role' => '999']);
        $response = $this->di['response'];
        $this->assertEquals(200, $response->getStatusCode(), $response->getBody());
        $this->assertNotEquals(999, $this->di['model/auth']->currentUser()->getRole());
    }

}
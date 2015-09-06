<?php

class UserListingIntegrationTest extends \ToptalTimezone\TestUtils\ResourceIntegrationTest {

    protected function getDataSetData() {
        return [
            'users' => [
                ['id' => '10', 'name' => 'u1', 'email' => 'u1@t.c', 'role' => 0, 'password' => '123'],
                ['id' => '11', 'name' => 'u2', 'email' => 'u2@t.c', 'role' => 1, 'password' => '123'],
                ['id' => '12', 'name' => 'u3', 'email' => 'u3@t.c', 'role' => 999, 'password' => '123'],
            ]
        ];
    }

    public function testNotLoggedInCantSeeListing() {
        $this->prepareRequest('GET', '/users', []);
        $response = $this->di['response'];
        $this->assertEquals(403, $response->getStatusCode());
    }

    public function testUserRoleCantSeeListing() {
        $this->modifySessionData(['userId' => 10]);
        $this->prepareRequest('GET', '/users', []);
        $response = $this->di['response'];
        $this->assertEquals(403, $response->getStatusCode());
    }

    public function testManagerRoleCanSeeListing() {
        $this->modifySessionData(['userId' => 11]);
        $this->prepareRequest('GET', '/users', []);
        $response = $this->di['response'];
        $this->assertEquals(200, $response->getStatusCode());
        $responseData = json_decode($response->getBody());
        $this->assertCount(1, $responseData->users);
    }

    public function testAdminRoleCanSeeListing() {
        $this->modifySessionData(['userId' => 12]);
        $this->prepareRequest('GET', '/users', []);
        $response = $this->di['response'];
        $this->assertEquals(200, $response->getStatusCode());
        $responseData = json_decode($response->getBody());
        $this->assertCount(2, $responseData->users);
    }



}
<?php

class EditUserIntegrationTest extends \MDTimezone\TestUtils\ResourceIntegrationTest {

    protected function getDataSetData() {
        return [
            'users' => [
                ['id' => '10', 'name' => 'u1', 'email' => 'u1@t.c', 'role' => 0, 'password' => '123'],
                ['id' => '11', 'name' => 'u2', 'email' => 'u2@t.c', 'role' => 1, 'password' => '123'],
                ['id' => '12', 'name' => 'u3', 'email' => 'u3@t.c', 'role' => 999, 'password' => '123'],
                ['id' => '13', 'name' => 'u4', 'email' => 'u4@t.c', 'role' => 999, 'password' => '123'],
                ['id' => '14', 'name' => 'u5', 'email' => 'u5@t.c', 'role' => 1, 'password' => '123'],
            ],
            'timezones' => [
                ['id' => 10, 'user_id' => 13, 'name' => 'America/Sao_Paulo'],
            ]
        ];
    }

    public function testNotLoggedInCantEditUser() {
        $this->prepareRequest('POST', '/user/10', []);
        $response = $this->di['response'];
        $this->assertEquals(403, $response->getStatusCode());
    }

    public function testCantSeeEditSelf() {
        $this->modifySessionData(['userId' => 10]);
        $this->prepareRequest('GET', '/user/10', []);
        $response = $this->di['response'];
        $this->assertEquals(400, $response->getStatusCode());
    }

    public function testCantEditSelf() {
        $this->modifySessionData(['userId' => 10]);
        $this->prepareRequest('POST', '/user/10', ['name' => 'test']);
        $response = $this->di['response'];
        $this->assertEquals(400, $response->getStatusCode());
        $this->assertNotEquals('test', $this->di['model/users']->userById(10)->getName());
    }

    public function testUserCannotEditManager() {
        $this->modifySessionData(['userId' => 10]);
        $this->prepareRequest('POST', '/user/11', ['name' => 'test']);
        $response = $this->di['response'];
        $this->assertEquals(403, $response->getStatusCode());
        $this->assertNotEquals('test', $this->di['model/users']->userById(11)->getName());
    }

    public function testUserCannotEditAdmin() {
        $this->modifySessionData(['userId' => 10]);
        $this->prepareRequest('POST', '/user/12', ['name' => 'test']);
        $response = $this->di['response'];
        $this->assertEquals(403, $response->getStatusCode());
        $this->assertNotEquals('test', $this->di['model/users']->userById(12)->getName());
    }

    public function testManagerCannotEditAdmin() {
        $this->modifySessionData(['userId' => 11]);
        $this->prepareRequest('POST', '/user/12', ['name' => 'test']);
        $response = $this->di['response'];
        $this->assertEquals(403, $response->getStatusCode());
        $this->assertNotEquals('test', $this->di['model/users']->userById(12)->getName());
    }

    public function testManagerCannotEditManager() {
        $this->modifySessionData(['userId' => 11]);
        $this->prepareRequest('POST', '/user/14', ['name' => 'test']);
        $response = $this->di['response'];
        $this->assertEquals(403, $response->getStatusCode());
        $this->assertNotEquals('test', $this->di['model/users']->userById(14)->getName());
    }

    public function testAdminCanChangeAdmin() {
        $this->modifySessionData(['userId' => 12]);
        $this->prepareRequest('POST', '/user/13', ['name' => 'test']);
        $response = $this->di['response'];
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('test', $this->di['model/users']->userById(13)->getName());
    }

    public function testCannotDeleteSelf() {
        $this->modifySessionData(['userId' => 10]);
        $this->prepareRequest('DELETE', '/user/10', []);
        $response = $this->di['response'];
        $this->assertEquals(400, $response->getStatusCode());
    }

    public function testAdminCanDelete() {
        $this->modifySessionData(['userId' => 13]);
        $this->prepareRequest('DELETE', '/user/10', []);
        $response = $this->di['response'];
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertNull($this->di['model/users']->userById(10));
    }

    public function testManagerCannotDeleteManager() {
        $this->modifySessionData(['userId' => 14]);
        $this->prepareRequest('DELETE', '/user/11', []);
        $response = $this->di['response'];
        $this->assertEquals(403, $response->getStatusCode());
        $this->assertNotNull($this->di['model/users']->userById(11));
    }

    public function testCanDeleteUserWithTimezone() {
        $this->modifySessionData(['userId' => 12]);
        $this->prepareRequest('DELETE', '/user/13', []);
        $response = $this->di['response'];
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertNull($this->di['model/users']->userById(13));
    }

}

<?php

class LoginIntegrationTest extends \ToptalTimezone\TestUtils\ResourceIntegrationTest {

    protected function getDataSetData() {
        return [
            'users' => [
                ['id' => '10', 'name' => 'u1', 'email' => 'u1@t.c', 'role' => 0, 'password' => '$2y$10$BXlIRQK/lPiS4NyWg5X.3O1sx63dgTnERAYa2DF1QCUZdeUfXFC0C']
            ]
        ];
    }

    public function testCanLogin() {
        $this->prepareRequest('POST', '/user/login', ['email' => 'u1@t.c', 'password' => '123456']);
        $response = $this->di['response'];
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(10, $this->di['session']['userId']);
    }

    public function testCannotLoginWrongPassword() {
        $this->prepareRequest('POST', '/user/login', ['email' => 'u1@t.c', 'password' => '1234']);
        $response = $this->di['response'];
        $this->assertEquals(403, $response->getStatusCode());
    }

    public function testCannotLoginWrongUsername() {
        $this->prepareRequest('POST', '/user/login', ['email' => 'u2@t.c', 'password' => '123']);
        $response = $this->di['response'];
        $this->assertEquals(403, $response->getStatusCode());
    }

}

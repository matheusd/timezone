<?php

class RegisterUserIntegrationTest extends \ToptalTimezone\TestUtils\ResourceIntegrationTest {

    protected function getDataSetData() {
        return [
            'users' => [
                ['id' => '10', 'name' => 'u1', 'email' => 'u1@t.c', 'role' => 0, 'password' => '123']
            ]
        ];
    }

    public function testCannotUseUnmatchedPasswords() {
        $this->prepareRequest('POST', '/user/new', ['password' => '123456', 'password2' => '1234567']);
        $response = $this->di['response'];
        $this->assertEquals(400, $response->getStatusCode());
    }

    public function testCannotCreateExistingUser() {
        $this->prepareRequest('POST', '/user/new', ['password' => '123456', 'password2' => '123456',
            'name' => 'joe', 'email' => 'u1@t.c']);
        $response = $this->di['response'];
        $this->assertEquals(400, $response->getStatusCode(), $response->getBody());
        $responseData = json_decode($response->getBody());
        $this->assertEquals("ToptalTimezone\User\Control\UserExistsException", $responseData->class);
    }

    public function testCanCreateUser() {
        $this->prepareRequest('POST', '/user/new', ['password' => '123456', 'password2' => '123456',
            'name' => 'joe', 'email' => 'u3@t.c']);
        $response = $this->di['response'];
        $this->assertEquals(200, $response->getStatusCode(), $response->getBody());
    }

    public function testCannotCreateAdmin() {
        $this->prepareRequest('POST', '/user/new', ['password' => '123456', 'password2' => '123456',
            'name' => 'joe', 'email' => 'u3@t.c', 'role' => 999]);
        $response = $this->di['response'];
        $this->assertEquals(200, $response->getStatusCode(), $response->getBody());
        $respData = json_decode($response->getBody());
        $userId = $respData->id;
        $user = $this->di['model/users']->userById($userId);
        $this->assertNotNull($user);
        $this->assertNotEquals(999, $user->getRole());
    }

    
}
<?php

class LogoutIntegrationTest extends \ToptalTimezone\TestUtils\ResourceIntegrationTest {

    protected function getDataSetData() {
        return [
            'users' => [
                ['id' => '10', 'name' => 'u1', 'email' => 'u1@t.c', 'role' => 0, 'password' => '123']
            ]
        ];
    }

    public function testCanLogout() {
        $this->modifySessionData(['userId' => 10]);
        $this->prepareRequest('POST', '/user/logout', []);
        $response = $this->di['response'];
        $this->assertEquals(200, $response->getStatusCode(), $response->getBody());
        $this->assertFalse(isset($this->di['session']['userId']), 'UserId was not unset on session');
    }
}

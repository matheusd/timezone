<?php

class RegisterUserTestResource extends \ToptalTimezone\User\Control\RegisterUserResource {
    use \ToptalTimezone\TestUtils\SettableDataResource;
}

class RegisterUserResourceTest extends PHPUnit_Framework_TestCase {

    public function testCanCreateNewUser() {
        $resource = new RegisterUserTestResource();

        $user = $this->getMockBuilder('\ToptalTimezone\Orm\User')
                ->getMock();
        $user->expects($this->once())
                ->method('getId')
                ->willReturn(100);

        $users = $this
            ->getMockBuilder('\ToptalTimezone\User\Model\Users')            
            ->getMock();
        $users->expects($this->once())
            ->method('newUser')
            ->willReturn($user);

        $auth = $this
            ->getMockBuilder('\ToptalTimezone\User\Model\Auth')
            ->getMock();
        $auth->expects($this->once())
            ->method('login');

        $resource->users = $users;
        $resource->auth = $auth;
        $resource->setData((object) ['name' => 'joe', 'password' => '123456',
            'password2' => '123456']);
        $resource->post();
    }

    /**
     * @expectedException \ToptalTimezone\User\Control\PasswordException
     */
    public function testCannotUseSmallPassword() {
        $resource = new RegisterUserTestResource();

        $resource->setData((object) ['name' => 'joe', 'password' => '123',
            'password2' => '123']);
        $resource->post();
    }

    /**
     * @expectedException \ToptalTimezone\User\Control\PasswordException
     */
    public function testCannotUseUnmatchingPasswords() {
        $resource = new RegisterUserTestResource();

        $resource->setData((object) ['name' => 'joe', 'password' => '123456',
            'password2' => '1234567']);
        $resource->post();
    }

}
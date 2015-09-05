<?php


class UsersTest extends PHPUnit_Framework_TestCase {
    
    public function testCanCreateNewUser() {
        $users = new ToptalTimezone\User\Model\Users();
        $entityManager = $this
            ->getMockBuilder('\Doctrine\Common\Persistence\ObjectManager')
            ->disableOriginalConstructor()
            ->getMock();
        $entityManager->expects($this->once())
            ->method('persist');

        $validator = $this
            ->getMockBuilder('Symfony\Component\Validator\Validator\RecursiveValidator')
            ->disableOriginalConstructor()            
            ->getMock();
        $validator->expects($this->once())
                ->method('validate');

        $users->entityManager = $entityManager;
        $users->validator = $validator;
        $users->newUser((Object) ['name' => 'joe', 'email' => 'zig']);
    }

    /**
     * @expectedException \ToptalTimezone\Orm\ValidationException
     */
    public function testEmptyEmailRaisesException() {
        $users = new ToptalTimezone\User\Model\Users();
        $entityManager = $this
            ->getMockBuilder('\Doctrine\Common\Persistence\ObjectManager')
            ->disableOriginalConstructor()
            ->getMock();
        $entityManager->expects($this->never())
            ->method('persist');

        $builder = Symfony\Component\Validator\Validation::createValidatorBuilder();
        $builder->addMethodMapping('loadValidatorMetadata');
        $validator = $builder->getValidator();

        $users->entityManager = $entityManager;
        $users->validator = $validator;
        $users->newUser((Object) ['name' => 'joe', 'email' => '']);
    }
}
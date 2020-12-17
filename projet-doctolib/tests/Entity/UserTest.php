<?php

namespace App\Tests\Entity;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use App\Entity\User;

class UserTest extends KernelTestCase
{
    private $validator;

    protected function setUp()
    {
        self::bootKernel();
        $this->validator = self::$container->get("validator");

    }
    private function getUser(string $email = null, string $password = null){
        $user = (new User())->setEmail($email)->setPassword($password);
        return $user;
    }

// ***************** TEST DES GHETTERS ET SETTERS ******************************
    public function testGhetterAndSetterEmail()
    {
        $user = $this->getUser("loulou@hotmail.fr", null);
        $user->setEmail("loulou@hotmail.fr");
        $this->assertEquals("loulou@hotmail.fr",$user->getEmail());

    }
    public function testGhetterAndSetterPassword()
    {
        $user = $this->getUser(null, "Sam");
        $user->setPassword("sam");
        $this->assertEquals("sam",$user->getPassword());

    }

// ***************** TEST VALIDATION DES DONNEES ******************************
    public function testIsEmailValide()
    {
        $user = $this->getUser("loulou@hotmail.fr", "Sam");
        $user->setEmail("loulou@hotmail.fr");
        $errors = $this->validator->validate($user);

        $this->assertCount(0,$errors);

    }
    public function testIsPasswordValide()
    {
        $user = $this->getUser("loulou@hotmail.fr", "Sam");
        $user->setPassword("Sam");
        $errors = $this->validator->validate($user);

        $this->assertCount(0,$errors);

    }

// ***************** TEST NotBlank ******************************
    public function testNotValidBlank(){
            
        $user = $this->getUser(null, null);
        $errors = $this->validator->validate($user);
        $this->assertCount(2, $errors);
    
    }
}
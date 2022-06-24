<?php

namespace App\Tests\Entity;

use App\Entity\Prediction;
use App\Entity\Score;
use App\Entity\User;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    public function testIsTrue(): void
    {
        $user = new User();

        $user
            ->setEmail('test@user.com')
            ->setRoles(['ROLE_USER', 'ROLE_TEST'])
            ->setPassword('password')
            ->setPersonaname('TesTer')
            ->setTwitchId('twitchUserId');

        $this->assertTrue($user->getEmail() === 'test@user.com');
        $this->assertTrue($user->getUsername() === 'test@user.com');
        $this->assertTrue($user->getRoles() === ['ROLE_USER', 'ROLE_TEST']);
        $this->assertTrue($user->getPassword() === 'password');
        $this->assertTrue($user->getPersonaname() === 'TesTer');
        $this->assertTrue($user->getTwitchId() === 'twitchUserId');
    }

    public function testIsFalse(): void
    {
        $user = new User();

        $user
            ->setEmail('test@user.com')
            ->setRoles(['ROLE_USER', 'ROLE_TEST'])
            ->setPassword('password')
            ->setPersonaname('TesTer')
            ->setTwitchId('twitchUserId');

        $this->assertFalse($user->getEmail() === 'false@user.com');
        $this->assertFalse($user->getUsername() === 'false@user.com');
        $this->assertFalse($user->getRoles() === ['ROLE_USER', 'ROLE_FALSE']);
        $this->assertFalse($user->getPassword() === 'falsepassword');
        $this->assertFalse($user->getPersonaname() === 'FalseTesTer');
        $this->assertFalse($user->getTwitchId() === 'falsetwitchUserId');
    }

    public function testIsEmpty(): void
    {
        $user = new User();

        $this->assertEmpty($user->getEmail());
        $this->assertEmpty($user->getPassword());
        $this->assertEmpty($user->getPersonaname());
        $this->assertEmpty($user->getScores());
        $this->assertEmpty($user->getTwitchId());
    }

    public function testGetRemovePrediction()
    {
        $user = new User();
        $prediction = new Prediction();

        $this->assertEmpty($user->getPredictions());

        $user->addPrediction($prediction);
        $this->assertContains($prediction, $user->getPredictions());

        $user->removePrediction($prediction);
        $this->assertEmpty($user->getPredictions());
    }

    public function testGetRemoveScore()
    {
        $user = new User();
        $score = new Score();

        $this->assertEmpty($user->getScores());

        $user->addScore($score);
        $this->assertContains($score, $user->getScores());

        $user->removeScore($score);
        $this->assertEmpty($user->getScores());
    }
}

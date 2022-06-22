<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\Event;
use App\Entity\Driver;
use DateTime;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Symfony\Component\String\Slugger\SluggerInterface;

/**
 * @codeCoverageIgnore
 */
class AppFixtures extends Fixture implements FixtureGroupInterface
{
    private $slugger;

    public function __construct(SluggerInterface $slugger)
    {
        $this->slugger = $slugger;
    }

    public static function getGroups(): array
    {
        return ['base_db'];
    }

    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);
        $user = new User();
            $user->setEmail('admin@test.com');
            $user->setPassword('$2y$13$.1ruvuVJZXHjbmUvQbsZP.wUm8ayYIjUkassWNTxYPxWfvLw.9b7O');
            $user->setRoles(['ROLE_ADMIN']);
            $user->setPersonaname('admin');

            $manager->persist($user);

        for ($i = 0; $i < 20; $i++) {
            $user = new User();
            $user->setEmail('user'.$i.'@test.com');
            $user->setPassword('$2y$13$.1ruvuVJZXHjbmUvQbsZP.wUm8ayYIjUkassWNTxYPxWfvLw.9b7O');
            $user->setRoles(['ROLE_USER']);
            $user->setPersonaname('user'.$i);

            $manager->persist($user);
        }

        for($i = 0; $i < 20; $i++) {
                $driver = new Driver();
                // $seed = str_split('ABCDEFGHIJKLMNOPQRSTUVWXYZ');
                // shuffle($seed);
                // $code = implode(array_rand($seed,3));
                
                $driver->setFullname('prénom'.$i.' nom'.$i);
                $driver->setNumber($i);
                $driver->setIsActive(true);
                $driver->setDriverId('driver '.$i);
                $driver->setCode('D'.$i);
    
                $manager->persist($driver);
            }

        for($i = 0; $i < 20; $i++) {
                $event = new Event();
                $event->setName('course n° '.$i);
                $event->setDate(new DateTime('+'.$i.' days'));
                $event->setQualifyingDate(new DateTime('+'.$i.' days'));
                $event->setRound($i);
                $event->setSeason('2022');
                $event->setCircuitName('circuitName '.$i);
                $event->setLocality('locality '.$i);
                $event->setCountry('country '.$i);
                $event->setSlug($this->slugger->slug($event->getName()));
    
                $manager->persist($event);
        }

        $manager->flush();
    }
}

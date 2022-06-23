<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\Event;
use App\Entity\Driver;
use App\Entity\Prediction;
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
        $adminUser = new User();
            $adminUser->setEmail('admin@test.com');
            $adminUser->setPassword('$2y$13$.1ruvuVJZXHjbmUvQbsZP.wUm8ayYIjUkassWNTxYPxWfvLw.9b7O');
            $adminUser->setRoles(['ROLE_ADMIN']);
            $adminUser->setPersonaname('admin');

            $manager->persist($adminUser);

        for ($i = 1; $i <= 20; $i++) {
            $user = new User();
            $user->setEmail('user'.$i.'@test.com');
            $user->setPassword('$2y$13$.1ruvuVJZXHjbmUvQbsZP.wUm8ayYIjUkassWNTxYPxWfvLw.9b7O');
            $user->setRoles(['ROLE_USER']);
            $user->setPersonaname('user'.$i);

            $manager->persist($user);
        }

        $drivers = [];
        for($i = 1; $i <= 20; $i++) {

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
            $drivers[] = $driver;
        }

        $events = [];
        for($i = 1; $i <= 20; $i++) {
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
                $events[] = $event;
        }

        $prediction = new Prediction();
        $prediction
            ->setUser($adminUser)
            ->setEvent($events[0])
            ->setCreatedAt(new DateTime())
            ->setRaceCreatedAt(new DateTime())
            ->setPole($drivers[0])
            ->setTime('1:22.333')
            ->setFinishFirst($drivers[1])
            ->setFinishSecond($drivers[2])
            ->setFinishThird($drivers[3]);

        $manager->persist($prediction);

        $manager->flush();
    }
}

<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\Event;
use App\Entity\Driver;
use App\Entity\Prediction;
use App\Entity\Result;
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
        $users = [];
        $adminUser = new User();
            $adminUser->setEmail('admin@test.com');
            $adminUser->setPassword('$2y$13$.1ruvuVJZXHjbmUvQbsZP.wUm8ayYIjUkassWNTxYPxWfvLw.9b7O');
            $adminUser->setRoles(['ROLE_ADMIN']);
            $adminUser->setPersonaname('admin');

            $manager->persist($adminUser);
            $users[] = $adminUser;

        for ($i = 1; $i <= 20; $i++) {
            $user = new User();
            $user->setEmail('user'.$i.'@test.com');
            $user->setPassword('$2y$13$.1ruvuVJZXHjbmUvQbsZP.wUm8ayYIjUkassWNTxYPxWfvLw.9b7O');
            $user->setRoles(['ROLE_USER']);
            $user->setPersonaname('user'.$i);
            $users[] = $user;
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

        foreach ($users as $u) {
            $prediction = new Prediction();

            $pole = $drivers[rand(0, 19)];
            $QFirst = $drivers[rand(0, 19)];
            $QSecond = $drivers[rand(0, 19)];
            while ($QSecond === $QFirst) {
                $QSecond = $drivers[rand(0, 19)];
            }
            $QThird = $drivers[rand(0, 19)];
            while ($QThird === $QFirst || $QThird === $QSecond) {
                $QThird = $drivers[rand(0, 19)];
            }
            $prediction
                ->setUser($u)
                ->setEvent($events[0])
                ->setCreatedAt(new DateTime())
                ->setRaceCreatedAt(new DateTime())
                ->setPole($pole)
                ->setTime('1:'.rand(20, 30).'.'.rand(100, 999))
                ->setFinishFirst($QFirst)
                ->setFinishSecond($QSecond)
                ->setFinishThird($QThird);

            $manager->persist($prediction);
        }

        $result = new Result();
        $QFirst = $drivers[rand(0, 19)];
        $QSecond = $drivers[rand(0, 19)];
        while ($QSecond === $QFirst) {
            $QSecond = $drivers[rand(0, 19)];
        }
        $QThird = $drivers[rand(0, 19)];
        while ($QThird === $QFirst || $QThird === $QSecond) {
            $QThird = $drivers[rand(0, 19)];
        }
        $result
            ->setEvent($events[0])
            ->setPole($drivers[rand(0, 19)])
            ->setTime('1:23.456')
            ->setUpdatedAt(new DateTime())
            ->setFinishedFirst($QFirst)
            ->setFinishedSecond($QSecond)
            ->setFinishedThird($QThird);
        
        $manager->persist($result);


        $manager->flush();
    }
}

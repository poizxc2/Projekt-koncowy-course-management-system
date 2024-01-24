<?php

namespace App;

use App\Entity\CourseParticipant;
use App\Entity\DbInfo;
use App\Entity\Roles;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class Fixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // *******************************************************
        // php bin/console doctrine:fixtures:load --no-interaction
        // ********************************************************

        // create professor
        $professor = (new User())
            ->setEmail('professor@app.dev')
            ->setPassword(password_hash('password', PASSWORD_DEFAULT))
            ->setRoles([Roles::PROFESSOR])
            ->setFirstName('admin-first-name')
            ->setLastName('admin-last-name');
        $manager->persist($professor);

        // create students
        for ($i = 1; $i <= 10; $i++) {
            $participant = (new CourseParticipant())
                ->setUser(
                    (new User())
                        ->setEmail("student{$i}@app.dev")
                        ->setPassword(password_hash('password', PASSWORD_DEFAULT))
                        ->setRoles([Roles::STUDENT])
                        ->setFirstName("student{$i}-first-name")
                        ->setLastName("student{$i}-last-name")
                        ->setStudentNumber(rand(30000, 50000))
                );
            $manager->persist($participant);
        }

        // create Db-info
        $dbInfo1 = (new DbInfo())->setValue('MySQL');
        $dbInfo2 = (new DbInfo())->setValue('MongoDb');
        $manager->persist($dbInfo1);
        $manager->persist($dbInfo2);

        $manager->flush();
    }
}
<?php

namespace App\DataFixtures;

use App\Entity\Job;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class JobFixtures extends Fixture
{

    public function load(ObjectManager $manager)
    {
        $job1 = new Job();
        $job2 = new Job();

        $job1->setName('Dompteur');
        $job2->setName('Voltige');
        
        $manager->persist($job1);
        $manager->persist($job2);

        $manager->flush();
    }
}

<?php

namespace App\DataFixtures;

use App\Entity\Website;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class WebsiteFixture extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $website = new Website();
        $website->setTitle('Wild Circus');
        $website->setEmail('wild@circus.com');
        $website->setAdress('9 Allé Serr');
        $website->setCountry('France');
        $website->setPhone('+33 5 60 70 80 90');

        $manager->persist($website);

        $manager->flush();
    }
}

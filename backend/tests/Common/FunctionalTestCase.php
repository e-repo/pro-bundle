<?php

declare(strict_types=1);

namespace App\Tests\Common;

use Doctrine\ORM\EntityManager;
use Faker\Factory;
use Faker\Generator;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\DependencyInjection\ContainerInterface;

class FunctionalTestCase extends KernelTestCase
{
    protected ContainerInterface $publicContainer;
    protected Application $application;
    protected EntityManager $entityManager;
    protected Generator $faker;

    public function setUp(): void
    {
        $kernel = self::bootKernel();

        $this->application = new Application($kernel);
        $this->publicContainer = self::$kernel->getContainer();
        $this->faker = Factory::create();

        $this->entityManager = $this->publicContainer
            ->get('doctrine')
            ->getManager();
    }

    public function tearDown(): void
    {
        parent::tearDown();
    }
}

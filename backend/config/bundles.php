<?php

declare(strict_types=1);

return [
    Symfony\Bundle\FrameworkBundle\FrameworkBundle::class => [
        'all' => true,
    ],
    Nelmio\ApiDocBundle\NelmioApiDocBundle::class => [
        'all' => true,
    ],
    Symfony\Bundle\TwigBundle\TwigBundle::class => [
        'all' => true,
    ],
    Twig\Extra\TwigExtraBundle\TwigExtraBundle::class => [
        'all' => true,
    ],
    Symfony\Bundle\MonologBundle\MonologBundle::class => [
        'all' => true,
    ],
    Doctrine\Bundle\DoctrineBundle\DoctrineBundle::class => [
        'all' => true,
    ],
    Doctrine\Bundle\MigrationsBundle\DoctrineMigrationsBundle::class => [
        'all' => true,
    ],
    Symfony\Bundle\MakerBundle\MakerBundle::class => [
        'dev' => true,
    ],
    Symfony\Bundle\SecurityBundle\SecurityBundle::class => [
        'all' => true,
    ],
    DAMA\DoctrineTestBundle\DAMADoctrineTestBundle::class => [
        'test' => true,
    ],
    Liip\TestFixturesBundle\LiipTestFixturesBundle::class => [
        'test' => true,
    ],
    Doctrine\Bundle\FixturesBundle\DoctrineFixturesBundle::class => [
        'dev' => true,
        'test' => true,
    ],
    Lexik\Bundle\JWTAuthenticationBundle\LexikJWTAuthenticationBundle::class => [
        'all' => true,
    ],
    Gesdinet\JWTRefreshTokenBundle\GesdinetJWTRefreshTokenBundle::class => [
        'all' => true,
    ],
    Nelmio\CorsBundle\NelmioCorsBundle::class => [
        'all' => true,
    ],
    Zenstruck\Messenger\Test\ZenstruckMessengerTestBundle::class => [
        'test' => true,
    ],
    Oneup\FlysystemBundle\OneupFlysystemBundle::class => [
        'all' => true,
    ],
    Intervention\Image\Symfony\InterventionImageBundle::class => [
        'all' => true,
    ],
];

<?php

declare(strict_types=1);

namespace Auth\User\Subscriber;

use Auth\User\Domain\Entity\Event\UserCreatedEvent;
use Common\Application\Bus\EventListenerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

final readonly class UserCreatedListener implements EventListenerInterface
{
    public function __construct(
        private MailerInterface $mailer,
        private Environment $twig,
        private string $appEmail,
        private string $appDomain,
    ) {
    }

    /**
     * @throws SyntaxError
     * @throws TransportExceptionInterface
     * @throws RuntimeError
     * @throws LoaderError
     */
    public function __invoke(UserCreatedEvent $event): void
    {
        $mail = $this->makeConfirmMessage($event->email, $event->emailConfirmToken);

        $this->mailer->send($mail);
    }

    /**
     * @throws RuntimeError
     * @throws SyntaxError
     * @throws LoaderError
     */
    private function makeConfirmMessage(string $email, string $emailConfirmToken): Email
    {
        return (new TemplatedEmail())
            ->from($this->appEmail)
            ->to($email)
            ->html($this->twig->render('mail/auth/signup.html.twig', [
                'token' => $emailConfirmToken,
                'appDomain' => $this->appDomain
            ]));
    }
}

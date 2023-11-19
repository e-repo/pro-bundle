<?php

declare(strict_types=1);

namespace Auth\User\Command\SignUp;

use Auth\User\Domain\Entity\EmailVo;
use Auth\User\Domain\Entity\IdVo;
use Auth\User\Domain\Entity\NameVo;
use Auth\User\Domain\Entity\Specification\UniqueEmailSpecification;
use Auth\User\Domain\Entity\User;
use Auth\User\Domain\Repository\UserRepositoryInterface;
use Auth\User\Domain\Service\PasswordHasher\Hasher;
use Common\Application\Bus\CommandHandlerInterface;
use Common\Application\FlusherInterface;
use Ramsey\Uuid\Uuid;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

final readonly class Handler implements CommandHandlerInterface
{
    public function __construct(
        private Hasher $hasher,
        private FlusherInterface $flusher,
        private UserRepositoryInterface $userRepository,
        private UniqueEmailSpecification $uniqueEmailSpecification,
        private MailerInterface $mailer,
        private Environment $twig,
        private string $appEmail,
        private string $appDomain,
    ) {
    }

    public function __invoke(Command $command): void
    {
        $emailConfirmToken = Uuid::uuid4()->toString();
        $emailVo = new EmailVo($command->email);

        $user = new User(
            id: IdVo::next(),
            name: new NameVo($command->firstName),
            email: $emailVo,
            password: $command->password,
            uniqueEmailSpecification: $this->uniqueEmailSpecification,
            hasher: $this->hasher,
            emailConfirmToken: $emailConfirmToken,
        );

        $this->userRepository->add($user);
        $this->flusher->flush();

        $this->mailer->send(
            $this->makeConfirmMessage($emailVo, $emailConfirmToken)
        );
    }

    /**
     * @throws RuntimeError
     * @throws SyntaxError
     * @throws LoaderError
     */
    private function makeConfirmMessage(EmailVo $email, string $emailConfirmToken): Email
    {
        return (new TemplatedEmail())
            ->from($this->appEmail)
            ->to($email->value)
            ->html($this->twig->render('mail/auth/signup.html.twig', [
                'token' => $emailConfirmToken,
                'appDomain' => $this->appDomain
            ]));
    }
}

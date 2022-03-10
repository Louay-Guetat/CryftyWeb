<?php

namespace App\Services\Mailer;

use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;

class MailerService
{
    private $mailer;

    /**
     * @param MailerInterface $mailer
     */
    public function __construct(MailerInterface  $mailer)
    {
        $this->mailer = $mailer;
    }


    /**
     * @throws TransportExceptionInterface
     */
    public function sendWalletVerificationEmail(string $toMail, array $variables){
        $email = (new TemplatedEmail())
            ->from('khalilrezgui1607@gmail.com')
            ->to($toMail)
            ->subject('Verify You Wallet')
            ->htmlTemplate('emails/walletVerification.html.twig')
            ->context($variables);
        $this->mailer->send($email);
    }

    /**
     * @throws TransportExceptionInterface
     */
    public function sendClientVerificationEmail(string $toMail, array $variables){
        $email = (new TemplatedEmail())
            ->from('khalilrezgui1607@gmail.com')
            ->to($toMail)
            ->subject('Verify Your acount')
            ->htmlTemplate('emails/ClientVerification.html.twig')
            ->context($variables);
        $this->mailer->send($email);
    }

    /**
     * @throws TransportExceptionInterface
     */
    public function sendClientReclamationEmail(string $toMail, array $variables){
        $email = (new TemplatedEmail())
            ->from('khalilrezgui1607@gmail.com')
            ->to($toMail)
            ->subject('Reclamation ')
            ->htmlTemplate('emails/ClientReclamation.html.twig')
            ->context($variables);
        $this->mailer->send($email);
    }
}
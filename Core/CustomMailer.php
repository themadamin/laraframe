<?php

namespace Core;

use Symfony\Component\Mailer\Envelope;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mailer\Transport\TransportInterface;
use Symfony\Component\Mime\RawMessage;

class CustomMailer implements MailerInterface
{
    protected TransportInterface $transport;

    public function __construct(protected string $dsn = 'smtp://mailhog:1025')
    {
        $this->transport = Transport::fromDsn($this->dsn);
    }
    public function send(RawMessage $message, ?Envelope $envelope = null): void
    {
        $this->transport->send($message, $envelope);
    }
}
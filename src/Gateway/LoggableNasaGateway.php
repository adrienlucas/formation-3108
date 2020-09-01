<?php

declare(strict_types=1);

namespace App\Gateway;


use Psr\Log\LoggerInterface;

class LoggableNasaGateway extends NasaGateway
{
    /** @var NasaGateway */
    private $actualGateway;

    private $logger;

    public function __construct(NasaGateway $actualGateway, LoggerInterface $logger)
    {
        $this->actualGateway = $actualGateway;
        $this->logger = $logger;
    }

    public function isEarthInDanger(): bool
    {
        $isInDanger = $this->actualGateway->isEarthInDanger();
        $this->logger->info($isInDanger ? 'The earth is in danger' : 'The earth is not in danger');
        return $isInDanger;
    }

}

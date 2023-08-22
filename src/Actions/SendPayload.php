<?php

namespace LaraDumps\LaraDumpsLibrary\Actions;

use LaraDumps\LaraDumpsLibrary\Payloads\Payload;
use League\Config\ConfigurationInterface;
use Ramsey\Uuid\Uuid;

final class SendPayload
{
    private string $appUrl;

    protected string $resource = '/api/dumps';

    public function __construct(ConfigurationInterface $config)
    {
        $this->appUrl = $config->get('DS_APP_HOST') . ':' . $config->get('DS_APP_PORT') . $this->resource;
    }

    /**
     * Sends Payload to the Desktop App
     */
    public function handle(array|Payload $payload): bool
    {
        $curlRequest = curl_init();

        curl_setopt_array($curlRequest, [
            CURLOPT_POST              => true,
            CURLOPT_RETURNTRANSFER    => true,
            CURLOPT_FOLLOWLOCATION    => true,
            CURLOPT_HTTPHEADER        => ['Content-Type: application/json', 'Accept: application/json'],
            CURLOPT_POSTFIELDS        => json_encode($payload),
            CURLOPT_URL               => $this->appUrl,
            CURLOPT_TIMEOUT           => 1,
            CURLOPT_CONNECTTIMEOUT_MS => 100,
        ]);

        $exec = curl_exec($curlRequest);

        /** @var string $exec */
        $result = json_decode($exec);

        /** @var null|object $result */
        if (is_null($result)) {
            return false;
        }

        return Uuid::isValid($result->id ?? '');
    }
}

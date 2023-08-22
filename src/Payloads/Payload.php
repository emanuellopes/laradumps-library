<?php

namespace LaraDumps\LaraDumpsLibrary\Payloads;

use LaraDumps\LaraDumpsLibrary\Concerns\Traceable;
use League\Config\ConfigurationInterface;

abstract class Payload
{
    use Traceable;

    public function __construct(private readonly ConfigurationInterface $config)
    {
    }

    private bool $dispatched = false;

    private string $notificationId;

    private ?string $dumpId = null;

    private ?bool $autoInvokeApp = null;

    abstract public function type(): string;

    public function setDispatch(bool $dispatched): void
    {
        $this->dispatched = $dispatched;
    }

    public function getDispatch(): bool
    {
        return $this->dispatched;
    }

    public function setDumpId(string $id): void
    {
        $this->dumpId = $id;
    }

    public function setNotificationId(string $notificationId): void
    {
        $this->notificationId = $notificationId;
    }

    public function content(): array
    {
        return [];
    }

    public function customHandle(): array
    {
        return [];
    }

    public function autoInvokeApp(?bool $enable = null): void
    {
        $this->autoInvokeApp = $enable;
    }

    public function toArray(): array
    {
        if (!empty($this->customHandle())) {
            $ideHandle = $this->customHandle();
        }

        if (!defined('LARADUMPS_REQUEST_ID')) {
            define('LARADUMPS_REQUEST_ID', uniqid('laradumps', true));
        }

        $dateTime = date('H:i:s');

        if (function_exists('now')) {
            $dateTime = now()->format('H:i:s');
        };

        return [
            'id'         => $this->notificationId,
            'request_id' => LARADUMPS_REQUEST_ID,
            'sf_dump_id' => $this->dumpId,
            'type'       => $this->type(),
            'meta'       => [
                'laradumps_version' => $this->getInstalledVersion(),
                'auto_invoke_app'   => $this->autoInvokeApp ?? $this->config->get('DS_AUTO_INVOKE_APP'),
            ],
            $this->type() => $this->content(),
            'ide_handle'  => $this->config->get('DS_PREFERRED_IDE'), //TODO: check if this is right
            'date_time'   => $dateTime,
        ];
    }

    public function getInstalledVersion(): ?string
    {
        if (class_exists(\Composer\InstalledVersions::class)) {
            try {
                return \Composer\InstalledVersions::getVersion('emanuellopes/laradumps-library');
            } catch (\Exception) {
                return '0.0.0';
            }
        }

        return '0.0.0';
    }
}

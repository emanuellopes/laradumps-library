<?php

namespace LaraDumps\LaraDumpsLibrary\Payloads;

use League\Config\ConfigurationInterface;

final class ScreenPayload extends Payload
{
    public function __construct(
        readonly ConfigurationInterface $config,
        public string $name,
        public int $raiseIn = 0,
    ) {
        parent::__construct($config);
    }

    public function type(): string
    {
        return 'screen';
    }

    /** @return array<string|mixed> */
    public function content(): array
    {
        return [
            'screen_name' => $this->name,
            'raise_in'    => $this->raiseIn,
        ];
    }
}

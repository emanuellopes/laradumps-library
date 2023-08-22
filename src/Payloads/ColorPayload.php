<?php

namespace LaraDumps\LaraDumpsLibrary\Payloads;

use League\Config\ConfigurationInterface;

final class ColorPayload extends Payload
{
    public function __construct(
        readonly ConfigurationInterface $config,
        public string $color
    ) {
        parent::__construct($config);
    }

    public function type(): string
    {
        return 'color';
    }

    /** @return array<string> */
    public function content(): array
    {
        return [
            'color' => $this->color,
        ];
    }
}

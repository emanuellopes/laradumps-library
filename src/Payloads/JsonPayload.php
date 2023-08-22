<?php

namespace LaraDumps\LaraDumpsLibrary\Payloads;

use League\Config\ConfigurationInterface;

final class JsonPayload extends Payload
{
    public function __construct(
        readonly ConfigurationInterface $config,
        public string $string,
    ) {
        parent::__construct($config);
    }

    public function type(): string
    {
        return 'json';
    }

    public function content(): array
    {
        return [
            'string'           => $this->string,
            'original_content' => $this->string,
        ];
    }
}

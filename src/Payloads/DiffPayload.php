<?php

namespace LaraDumps\LaraDumpsLibrary\Payloads;

use League\Config\ConfigurationInterface;

final class DiffPayload extends Payload
{
    public function __construct(
        readonly ConfigurationInterface $config,
        public mixed $argument,
        public bool $splitDiff,
    ) {
        parent::__construct($config);
    }

    public function type(): string
    {
        return 'diff';
    }

    /** @return array<string, mixed> */
    public function content(): array
    {
        return [
            'argument'  => $this->argument,
            'splitDiff' => $this->splitDiff,
        ];
    }
}

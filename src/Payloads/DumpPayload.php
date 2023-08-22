<?php

namespace LaraDumps\LaraDumpsLibrary\Payloads;

use League\Config\ConfigurationInterface;

class DumpPayload extends Payload
{
    public function __construct(
        readonly ConfigurationInterface $config,
        public mixed $dump,
        public mixed $originalContent = null,
        public ?string $variableType = null,
    ) {
        parent::__construct($config);
    }

    public function type(): string
    {
        return 'dump';
    }

    public function content(): array
    {
        return [
            'dump'             => $this->dump,
            'original_content' => $this->originalContent,
            'variable_type'    => $this->variableType,
        ];
    }
}

<?php

namespace LaraDumps\LaraDumpsLibrary\Payloads;

use League\Config\ConfigurationInterface;

final class LabelPayload extends Payload
{
    /**
     * ColorPayload constructor.
     */
    public function __construct(
        readonly ConfigurationInterface $config,
        public string $label
    ) {
        parent::__construct($config);
    }

    public function type(): string
    {
        return 'label';
    }

    public function content(): array
    {
        return [
            'label' => $this->label,
        ];
    }
}

<?php

namespace LaraDumps\LaraDumpsLibrary\Payloads;

use League\Config\ConfigurationInterface;

final class TimeTrackPayload extends Payload
{
    /**
     * Clock script executiontime
     */
    public function __construct(
        readonly ConfigurationInterface $config,
        public string $reference,
        public bool $stop = false
    ) {
        parent::__construct($config);
    }

    public function type(): string
    {
        return 'time_track';
    }

    /** @return array<string, mixed> */
    public function content(): array
    {
        $content = [
            'tracker_id' => uniqid('laradump', true),
            'time'       => microtime(true),
            'label'      => $this->reference,
        ];

        if ($this->stop) {
            $content['end_time'] = microtime(true);
        }

        return $content;
    }
}

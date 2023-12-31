<?php

namespace LaraDumps\LaraDumpsLibrary\Payloads;

use Carbon\Carbon;
use LaraDumps\LaraDumpsLibrary\Support\Dumper;
use League\Config\ConfigurationInterface;

final class BenchmarkPayload extends Payload
{
    public function __construct(
        readonly ConfigurationInterface $config,
        private mixed $args
    ) {
        parent::__construct($config);
    }

    public function type(): string
    {
        return 'table_v2';
    }

    public function content(): array
    {
        $results      = [];
        $fastestLabel = '';
        $fastestTime  = PHP_INT_MAX;

        /** @var array  $closures */
        $closures = $this->args;

        if (count($closures) === 1 && is_array($closures[0])) {
            $closures = $closures[0];
        }

        foreach ($closures as $label => $closure) {
            $startsAt = Carbon::now();
            /** @var callable $result */
            $result = $closure();
            $endsAt = Carbon::now();

            $totalTime = $endsAt->diffInMilliseconds($startsAt);
            $label     = is_int($label) ? 'Closure ' . $label : $label;

            $results[$label] = [
                'Start Time' => $startsAt->toDateTimeString(),
                'End Time'   => $endsAt->toDateTimeString(),
                'Total Time' => $totalTime . ' ms',
                'Result'     => $result,
            ];

            if ($totalTime < $fastestTime) {
                $fastestLabel = $label;
                $fastestTime  = $totalTime;
            }
        }

        $results['Fastest'] = $fastestLabel;

        return [
            'label'  => 'Benchmark',
            'values' => array_map(static fn ($result, $index) => Dumper::dump($result), $results), /** @phpstan-ignore-line  */
        ];
    }

    public function customHandle(): array
    {
        return [];
    }
}

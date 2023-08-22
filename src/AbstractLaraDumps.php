<?php

namespace LaraDumps\LaraDumpsLibrary;

use LaraDumps\LaraDumpsLibrary\Actions\{SendPayload, Support};
use LaraDumps\LaraDumpsLibrary\Concerns\Colors;
use LaraDumps\LaraDumpsLibrary\Config\AppConfigSchema;
use LaraDumps\LaraDumpsLibrary\Payloads\{BenchmarkPayload,
    ClearPayload,
    CoffeePayload,
    ColorPayload,
    DumpPayload,
    JsonPayload,
    LabelPayload,
    Payload,
    PhpInfoPayload,
    ScreenPayload,
    TablePayload,
    TimeTrackPayload,
    ValidJsonPayload,
    ValidateStringPayload
};
use LaraDumps\LaraDumpsLibrary\Support\Dumper;
use League\Config\Configuration;
use Ramsey\Uuid\Uuid;

abstract class AbstractLaraDumps
{
    use Colors;

    private readonly Configuration $configuration;

    private bool $dispatched = false;

    public function __construct(
        public string $notificationId = '',
        private array $trace = [],
    ) {
        $this->notificationId = !empty($notificationId) ? $this->notificationId : Uuid::uuid4()->toString();
        $this->configuration  = new Configuration();
        $this->loadConfigurationSchemas();
        $this->loadConfig();
    }

    public function loadConfigFile(array $data): void
    {
        $this->configuration->merge($data);
    }

    protected function loadConfigurationSchemas(): void
    {
        $applicationSchema = new AppConfigSchema();
        $this->configuration->addSchema($applicationSchema->getKey(), $applicationSchema->getSchema());
    }

    protected function beforeWrite(mixed $args): \Closure
    {
        return function () use ($args) {
            if (is_string($args) && Support::isJson($args)) {
                return [
                    new JsonPayload($this->configuration->reader(), $args),
                    uniqid('laradumps', true),
                ];
            }

            [$pre, $id] = Dumper::dump($args);

            return [
                new DumpPayload($pre, $args, variableType: gettype($args)),
                $id,
            ];
        };
    }

    public function send(Payload $payload): Payload
    {
        if (!empty($this->trace)) {
            $payload->setTrace($this->trace);
        }

        $payload->setNotificationId($this->notificationId);

        $sendPayload = new SendPayload($this->configuration->reader());

        $response = $sendPayload->handle(
            $payload->toArray()
        );

        if ($response) {
            $payload->setDispatch(true);
        }

        return $payload;
    }

    public function write(mixed $args = null, ?bool $autoInvokeApp = null, array $trace = []): self
    {
        [$payload, $id] = $this->beforeWrite($args)();

        $payload->autoInvokeApp($autoInvokeApp);
        $payload->setDumpId($id);
        $payload->setTrace($trace);

        $this->send($payload);

        return $this;
    }

    /**
     * Send custom color
     */
    public function color(string $color): self
    {
        $payload = new ColorPayload($this->configuration->reader(), $color);
        $this->send($payload);

        return $this;
    }

    /**
     * Add new screen
     */
    public function s(string $screen): self
    {
        return $this->toScreen($screen);
    }

    /**
     * Add new screen
     *
     * @param int $raiseIn Delay in seconds for the app to raise and focus
     */
    public function toScreen(
        string $screenName,
        int    $raiseIn = 0
    ): self {
        $payload = new ScreenPayload($this->configuration->reader(), $screenName, $raiseIn);
        $this->send($payload);

        return $this;
    }

    /**
     * Send custom label
     */
    public function label(string $label): self
    {
        $payload = new LabelPayload($this->configuration->reader(), $label);
        $payload->setTrace(debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1)[0]);

        $this->send($payload);

        return $this;
    }

    /**
     * Send dump and die
     */
    public function die(string $status = ''): void
    {
        exit($status);
    }

    /**
     * Clear screen
     */
    public function clear(): self
    {
        $this->send(new ClearPayload($this->configuration->reader()));

        return $this;
    }

    /**
     * Grab a coffee!
     */
    public function coffee(): self
    {
        $this->send(new CoffeePayload($this->configuration->reader()));

        return $this;
    }

    /**
     * Send JSON data and validate
     */
    public function isJson(): self
    {
        $payload = new ValidJsonPayload($this->configuration->reader());
        $payload->setTrace(debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1)[0]);

        $this->send($payload);

        return $this;
    }

    /**
     * Checks if content contains string.
     *
     * @param bool $caseSensitive Search is case-sensitive
     * @param bool $wholeWord Search for the whole words
     */
    public function contains(string $content, bool $caseSensitive = false, bool $wholeWord = false): self
    {
        $payload = new ValidateStringPayload($this->configuration->reader(), 'contains');
        $payload->setContent($content)
            ->setCaseSensitive($caseSensitive)
            ->setWholeWord($wholeWord)
            ->setTrace(debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1)[0]);

        $this->send($payload);

        return $this;
    }

    /**
     * Send PHPInfo
     */
    public function phpinfo(): self
    {
        $payload = new PhpInfoPayload($this->configuration->reader());
        $payload->setTrace(debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1)[0]);

        $this->send($payload);

        return $this;
    }

    /**
     * Send Table
     */
    public function table(mixed $data = [], string $name = ''): self
    {
        $payload = new TablePayload($this->configuration->reader(), $data, $name);
        $payload->setTrace(debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1)[0]);

        $this->send($payload);

        return $this;
    }

    /**
     * Starts clocking a code block execution time
     *
     * @param string $reference Unique name for this time clocking
     */
    public function time(string $reference): void
    {
        $payload = new TimeTrackPayload($this->configuration->reader(), $reference);
        $payload->setTrace(debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1)[0]);

        $this->send($payload);
        $this->label($reference);
    }

    /**
     * Stops clocking a code block execution time
     *
     * @param string $reference Unique name called on ds()->time()
     */
    public function stopTime(string $reference): void
    {
        $payload = new TimeTrackPayload($this->configuration->reader(), $reference, true);
        $payload->setTrace(debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1)[0]);

        $this->send($payload);
    }

    /**
     * Benchmarking
     */
    public function benchmark(mixed ...$args): self
    {
        $benchmarkPayload = new BenchmarkPayload($this->configuration->reader(), $args);
        $this->send($benchmarkPayload);

        return $this;
    }

    public function getDispatch(): bool
    {
        return $this->dispatched;
    }

    abstract public function loadConfig(): void;
}

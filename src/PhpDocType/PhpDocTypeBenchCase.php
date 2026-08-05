<?php

declare(strict_types=1);

namespace Phplrt\Bench\PhpDocType;

use PhpBench\Attributes\Iterations;
use PhpBench\Attributes\Revs;
use PhpBench\Attributes\Warmup;
use Phplrt\Bench\BenchCase;

abstract readonly class PhpDocTypeBenchCase extends BenchCase
{
    /**
     * The directory under "tools/" holding the composer project of the tool.
     *
     * @var non-empty-string
     */
    protected const string TOOL = '';

    /**
     * @var list<non-empty-string>
     */
    private const array SAMPLES = [
        'common-type-10b.txt',
        'common-type-64b.txt',
        'common-type-256b.txt',
        'common-type-1k.txt',
        'common-type-4k.txt',
        'common-type-16k.txt',
        'common-type-32k.txt',
        'common-type-100k.txt',
        'workload.txt',
    ];

    /**
     * The types of every sample, read and split before the measurement.
     *
     * @var array<non-empty-string, list<non-empty-string>>
     */
    protected array $types;

    public function prepare(): void
    {
        // Load samples
        if (!isset($this->types)) {
            $types = [];

            foreach (self::SAMPLES as $sample) {
                $sampleString = (string) @\file_get_contents(__DIR__ . '/Sample/' . $sample);
                $sampleArray = \explode("\n", $sampleString);

                $types[$sample] = $sampleArray;
            }

            $this->types = $types;
        }

        // Prepare cache directory
        if (!\is_dir(self::CACHE_DIRECTORY)) {
            @\mkdir(self::CACHE_DIRECTORY, recursive: true);
        }

        // Boot
        $this->boot(self::install(static::TOOL));
    }

    /**
     * @param non-empty-string $directory
     */
    abstract protected function boot(string $directory): void;

    abstract protected function parse(string $content): void;

    /**
     * @return non-empty-string
     */
    protected static function grammar(string $name): string
    {
        return __DIR__ . '/Grammar/' . $name;
    }

    #[Revs(1)]
    #[Warmup(0)]
    #[Iterations(10)]
    public function benchColdStart(): void
    {
        foreach ($this->types['common-type-10b.txt'] as $type) {
            $this->parse($type);
        }
    }

    #[Revs(1000)]
    #[Warmup(200)]
    public function benchCommonType10b(): void
    {
        foreach ($this->types['common-type-10b.txt'] as $type) {
            $this->parse($type);
        }
    }

    #[Revs(500)]
    #[Warmup(100)]
    public function benchCommonType64b(): void
    {
        foreach ($this->types['common-type-64b.txt'] as $type) {
            $this->parse($type);
        }
    }

    #[Revs(200)]
    #[Warmup(40)]
    public function benchCommonType256b(): void
    {
        foreach ($this->types['common-type-256b.txt'] as $type) {
            $this->parse($type);
        }
    }

    #[Revs(50)]
    #[Warmup(10)]
    public function benchCommonType1k(): void
    {
        foreach ($this->types['common-type-1k.txt'] as $type) {
            $this->parse($type);
        }
    }

    #[Revs(20)]
    #[Warmup(4)]
    public function benchCommonType4k(): void
    {
        foreach ($this->types['common-type-4k.txt'] as $type) {
            $this->parse($type);
        }
    }

    #[Revs(5)]
    #[Warmup(2)]
    public function benchCommonType16k(): void
    {
        foreach ($this->types['common-type-16k.txt'] as $type) {
            $this->parse($type);
        }
    }

    #[Revs(3)]
    #[Warmup(1)]
    public function benchCommonType32k(): void
    {
        foreach ($this->types['common-type-32k.txt'] as $type) {
            $this->parse($type);
        }
    }

    #[Revs(1)]
    #[Warmup(1)]
    public function benchCommonType100k(): void
    {
        foreach ($this->types['common-type-100k.txt'] as $type) {
            $this->parse($type);
        }
    }

    #[Revs(5)]
    #[Warmup(1)]
    public function benchWorkload(): void
    {
        foreach ($this->types['workload.txt'] as $type) {
            $this->parse($type);
        }
    }
}

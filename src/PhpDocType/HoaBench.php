<?php

declare(strict_types=1);

namespace Phplrt\Bench\PhpDocType;

use Hoa\Compiler\Llk\Llk;
use Hoa\Compiler\Llk\Parser;
use Hoa\File\Read;
use PhpBench\Attributes\BeforeMethods;
use PhpBench\Attributes\Iterations;
use PhpBench\Attributes\RetryThreshold;

#[Iterations(5)]
#[RetryThreshold(5)]
#[BeforeMethods('prepare')]
final readonly class HoaBench extends PhpDocTypeBenchCase
{
    protected const string TOOL = 'hoa';

    private Parser $parser;

    protected function boot(string $directory): void
    {
        if (isset($this->parser)) {
            return;
        }

        \error_reporting(\E_ERROR | \E_PARSE);
        \ini_set('display_errors', '0');

        $this->parser = Llk::load(new Read(self::grammar('PhpDocType.pp')));
    }

    protected function parse(string $content): void
    {
        $this->parser->parse($content);
    }
}

<?php

declare(strict_types=1);

namespace Phplrt\Bench\PhpDocType;

use PhpBench\Attributes\BeforeMethods;
use PhpBench\Attributes\Iterations;
use PhpBench\Attributes\RetryThreshold;
use PHPStan\PhpDocParser\Lexer\Lexer;
use PHPStan\PhpDocParser\Parser\ConstExprParser;
use PHPStan\PhpDocParser\Parser\TokenIterator;
use PHPStan\PhpDocParser\Parser\TypeParser;
use PHPStan\PhpDocParser\ParserConfig;

#[Iterations(5)]
#[RetryThreshold(5)]
#[BeforeMethods('prepare')]
final readonly class PhpStanBench extends PhpDocTypeBenchCase
{
    protected const string TOOL = 'phpstan';

    private Lexer $lexer;

    private TypeParser $parser;

    protected function boot(string $directory): void
    {
        if (isset($this->parser)) {
            return;
        }

        $config = new ParserConfig([]);

        $this->lexer = new Lexer($config);
        $this->parser = new TypeParser($config, new ConstExprParser($config));
    }

    protected function parse(string $content): void
    {
        $this->parser->parse(new TokenIterator(
            $this->lexer->tokenize($content),
        ));
    }
}

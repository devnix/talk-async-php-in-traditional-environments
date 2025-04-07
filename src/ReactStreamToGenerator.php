<?php

namespace App;

use React\Promise\Deferred;
use React\Stream\ReadableStreamInterface;
use function React\Async\await;

class ReactStreamToGenerator
{
    private ?Deferred $promise = null;

    public function __construct(private ReadableStreamInterface $stream)
    {
        $this->stream->on('end', function (): void {
            $this->promise->resolve(null);
        });
    }

    public function getGenerator(): \Generator
    {
        while ($this->stream->isReadable()) {
            $chunk = $this->waitForChunk();
            if ($chunk === null) {
                break;
            }

            yield $chunk;
        }
    }

    private function waitForChunk()
    {
        $this->promise = new Deferred();

        $this->stream->once('data', function ($data) : void {
            $this->promise->resolve($data);
        });

        return await($this->promise->promise());
    }
}
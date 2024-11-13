<?php

namespace JustBetter\XhprofProfiler\Model\Profiler;

use JustBetter\XhprofProfiler\Api\DriverInterface;

class XhprofProfiler
{
    public function __construct(
        protected DriverInterface $driver,
        protected array           $tags = []
    )
    {
        $this->tags = $tags;
    }

    public function handle(): void
    {
        $this->driver->start();
    }

    public function terminate(array $tags = []): void
    {
        $this->driver->end(array_merge($this->tags ?? [], $tags));
    }
}

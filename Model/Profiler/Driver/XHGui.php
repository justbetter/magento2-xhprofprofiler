<?php

namespace JustBetter\XhprofProfiler\Model\Profiler\Driver;

use Magento\Framework\App\DeploymentConfig;
use Xhgui\Profiler\Profiler;
use Xhgui\Profiler\ProfilingFlags;

class XHGui implements \JustBetter\XhprofProfiler\Api\DriverInterface
{
    public Profiler $driver;

    public function __construct(
        protected DeploymentConfig $deploymentConfig,
        array $config = []
    )
    {
        $this->initializeDriver($config);
    }

    private function initializeDriver(array $config): void
    {
        $this->driver = new Profiler($config);
    }

    public function start(): void
    {
        $this->driver->enable();
    }

    public function end(array $tags = []): void
    {
        $this->driver->save($this->driver->disable());
    }
}

<?php

namespace JustBetter\XhprofProfiler\Model\Profiler;

use Magento\Framework\App\DeploymentConfig;
use Magento\Framework\Exception\FileSystemException;
use Magento\Framework\Exception\RuntimeException;
use SpiralPackages\Profiler\Driver\XhprofDriver;
use SpiralPackages\Profiler\Storage\WebStorage;
use Symfony\Component\HttpClient\NativeHttpClient;

class XhprofProfiler
{
    public const HEADER = 'X-Xhprof-Enabled';

    public const IGNORED_FUNCTIONS_KEY = 'ignored_functions';

    public array $tags;

    public function __construct(
        protected XhprofDriver     $driver,
        protected DeploymentConfig $deploymentConfig,
        array $tags = []
    )
    {
        $this->tags = $tags;
    }

    public function handle(): void
    {
        $this->driver->start(
            [
                self::IGNORED_FUNCTIONS_KEY => ['SpiralPackages\Profiler\Profiler::end'],
            ]
        );
    }

    /**
     * @throws FileSystemException
     * @throws RuntimeException
     */
    public function terminate(array $tags = []): void
    {
        $result = $this->driver->end();
        $endpoint = $this->deploymentConfig->get('xhprofprofiler/endpoint');
        $appName = $this->deploymentConfig->get('xhprofprofiler/app_name');
        if (!empty($appName) && !empty($endpoint) && is_string($endpoint) && is_string($appName)) {
            $storage = new WebStorage(new NativeHttpClient(), $endpoint);
            $storage->store(
                $appName,
                \array_merge($this->tags ?? [], $tags),
                new \DateTimeImmutable(),
                $result
            );
        }
    }
}

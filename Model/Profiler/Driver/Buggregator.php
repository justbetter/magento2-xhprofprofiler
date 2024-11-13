<?php

namespace JustBetter\XhprofProfiler\Model\Profiler\Driver;

use JustBetter\XhprofProfiler\Api\DriverInterface;
use Magento\Framework\App\DeploymentConfig;
use Magento\Framework\Exception\FileSystemException;
use Magento\Framework\Exception\RuntimeException;
use SpiralPackages\Profiler\Driver\XhprofDriver;
use SpiralPackages\Profiler\Storage\WebStorage;
use Symfony\Component\HttpClient\NativeHttpClient;


class Buggregator implements DriverInterface
{
    public const IGNORED_FUNCTIONS_KEY = 'ignored_functions';

    public function __construct(
        protected XhprofDriver     $driver,
        protected DeploymentConfig $deploymentConfig,
    )
    {}

    public function start(): void
    {
        $this->driver->start(
            [
                self::IGNORED_FUNCTIONS_KEY => ['SpiralPackages\Profiler\Profiler::end'],
            ]
        );
    }

    /**
     * Terminates the current profiling session and stores the results.
     * @param array $tags Optional tags to attach to the profiling data.
     * @return void
     * @throws FileSystemException
     * @throws RuntimeException
     */
    public function end(array $tags = []): void
    {
        $result = $this->driver->end();
        $endpoint = $this->deploymentConfig->get('xhprofprofiler/endpoint');
        $appName = $this->deploymentConfig->get('xhprofprofiler/app_name');
        if (!empty($appName) && !empty($endpoint) && is_string($endpoint) && is_string($appName)) {
            $storage = new WebStorage(new NativeHttpClient(), $endpoint);
            $storage->store(
                $appName,
                $tags,
                new \DateTimeImmutable(),
                $result
            );
        }
    }
}

<?php

namespace JustBetter\XhprofProfiler\Plugin;

use JustBetter\XhprofProfiler\Model\Profiler\XhprofProfiler;
use Magento\Framework\App\Request\Http;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\AppInterface as Application;
use Throwable;

class AppInterfacePlugin
{
    public const HEADER = 'X-Xhprof-Enabled';

    public function __construct(
        protected XhprofProfiler $profiler,
        protected Http           $request
    )
    {
    }

    public function aroundLaunch(
        Application $subject,
        callable    $proceed,
    ): ResponseInterface
    {

        if (!$this->isEnabled()) {
            return $proceed();
        }

        $this->profiler->handle();
        $response = $proceed();
        $this->profiler->terminate([
            'method' => $this->request->getMethod(),
            'uri' => $this->request->getUriString()
        ]);
        return $response;
    }

    private function isEnabled(): bool
    {
        if ($this->request->getHeader(self::HEADER)) {
            return filter_var($this->request->getHeader(self::HEADER), FILTER_VALIDATE_BOOLEAN);
        }

        return isset($_ENV['XHPROF_ENABLED']) ? (bool)$_ENV['XHPROF_ENABLED'] : false;
    }
}

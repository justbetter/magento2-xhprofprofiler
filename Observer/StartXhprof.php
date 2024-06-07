<?php

namespace JustBetter\XhprofProfiler\Observer;

use JustBetter\XhprofProfiler\Model\Profiler\XhprofProfiler;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\App\Request\Http;
use Throwable;

class StartXhprof implements ObserverInterface
{
    public function __construct(
        protected XhprofProfiler $profiler
    )
    {}

    public function execute(Observer $observer): void
    {
        if ($this->isEnabled($observer->getRequest())) {
            $this->profiler->handle();
        }
    }

    public function isEnabled(Http $request): bool
    {
        if ($request->getHeader(XhprofProfiler::HEADER)) {
            return filter_var($request->getHeader(XhprofProfiler::HEADER), FILTER_VALIDATE_BOOLEAN);
        }

        try {
            return (bool) getenv('XHPROF_ENABLED');
        } catch (Throwable) {
            return false;
        }
    }
}

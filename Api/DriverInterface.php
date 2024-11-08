<?php

namespace JustBetter\XhprofProfiler\Api;

interface DriverInterface
{
    /**
     * @return void
     */
    public function start();

    /**
     * End profiling sessions and output data
     * @return void
     */
    public function end(array $tags);
}

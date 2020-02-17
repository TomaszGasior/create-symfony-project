<?php

namespace Mushrooms\CreateSymfonyProject\Symfony;

class Release
{
    /**
     * "Latest Long-Term Support Release"
     */
    public const LTS = 'lts';

    /**
     * "Latest Stable Release"
     */
    public const STABLE = 'stable';

    /**
     * "Unreleased version"
     */
    public const NEXT = 'next';

    private function __construct() {}
}

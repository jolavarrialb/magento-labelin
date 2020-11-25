<?php

declare(strict_types=1);

namespace Labelin\PitneyBowesRestApi\Api;

interface OauthInterface
{
    /**
     * @return string
     */
    public function getAuthToken(): string;
}

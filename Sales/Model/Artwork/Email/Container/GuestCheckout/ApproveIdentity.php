<?php

declare(strict_types=1);

namespace Labelin\Sales\Model\Artwork\Email\Container\GuestCheckout;

use Labelin\Sales\Model\Artwork\Email\Container\Identity;

class ApproveIdentity extends Identity
{
    /** @var array */
    protected $identity;

    public function setEmailIdentity(array $identity = []): self
    {
        $this->identity = $identity;

        return $this;
    }

    public function getEmailIdentity(): array
    {
        return $this->identity;
    }
}

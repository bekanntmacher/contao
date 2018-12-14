<?php

declare(strict_types=1);

/*
 * This file is part of Contao.
 *
 * (c) Leo Feyer
 *
 * @license LGPL-3.0-or-later
 */

namespace Contao\CoreBundle\OptIn;

use Contao\Model;

interface OptInTokenInterface
{
    /**
     * Returns the token identifier.
     */
    public function getIdentifier(): string;

    /**
     * Returns true if the token is valid.
     */
    public function isValid(): bool;

    /**
     * Confirms the token.
     */
    public function confirm(): void;

    /**
     * Returns true if the token has been confirmed.
     */
    public function isConfirmed(): bool;

    /**
     * Sends the token via e-mail.
     */
    public function send(string $subject = null, string $text = null): void;

    /**
     * Returns true if the token has been sent via e-mail.
     */
    public function hasBeenSent(): bool;

    /**
     * Returns the related model.
     */
    public function getRelatedModel(): ?Model;
}

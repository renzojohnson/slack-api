<?php

/**
 * Slack API
 *
 * @package   RenzoJohnson\Slack
 * @author    Renzo Johnson <hello@renzojohnson.com>
 * @copyright 2026 Renzo Johnson
 * @license   MIT
 * @link      https://renzojohnson.com
 */

declare(strict_types=1);

namespace RenzoJohnson\Slack\Exception;

class RateLimitException extends SlackException
{
    public function __construct(string $message = '', int $retryAfter = 0, array $errorData = [], ?\Throwable $previous = null)
    {
        parent::__construct($message, 429, $errorData, $previous);
        $this->retryAfter = $retryAfter;
    }

    private int $retryAfter;

    public function getRetryAfter(): int
    {
        return $this->retryAfter;
    }
}

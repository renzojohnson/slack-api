<?php

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

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

use RuntimeException;

class SlackException extends RuntimeException
{
    private array $errorData;

    public function __construct(string $message = '', int $code = 0, array $errorData = [], ?\Throwable $previous = null)
    {
        $this->errorData = $errorData;
        parent::__construct($message, $code, $previous);
    }

    public function getErrorData(): array
    {
        return $this->errorData;
    }
}

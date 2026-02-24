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

namespace RenzoJohnson\Slack\Webhook;

use RenzoJohnson\Slack\Http\Client;
use RenzoJohnson\Slack\Message\WebhookMessage;

final readonly class IncomingWebhook
{
    public function __construct(
        private string $webhookUrl,
        private int $timeout = 30,
    ) {}

    public function send(string|WebhookMessage $message): void
    {
        if (is_string($message)) {
            $message = new WebhookMessage($message);
        }

        Client::postWebhook($this->webhookUrl, $message->toArray(), $this->timeout);
    }
}

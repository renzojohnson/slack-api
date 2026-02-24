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

namespace RenzoJohnson\Slack\Message;

final readonly class WebhookMessage
{
    /**
     * @param array<array{type: string, text?: array}> $blocks
     */
    public function __construct(
        private string $text,
        private array $blocks = [],
        private ?string $username = null,
        private ?string $iconEmoji = null,
        private ?string $iconUrl = null,
        private ?string $channel = null,
    ) {}

    public function toArray(): array
    {
        $payload = [
            'text' => $this->text,
        ];

        if ($this->blocks !== []) {
            $payload['blocks'] = $this->blocks;
        }

        if ($this->username !== null) {
            $payload['username'] = $this->username;
        }

        if ($this->iconEmoji !== null) {
            $payload['icon_emoji'] = $this->iconEmoji;
        }

        if ($this->iconUrl !== null) {
            $payload['icon_url'] = $this->iconUrl;
        }

        if ($this->channel !== null) {
            $payload['channel'] = $this->channel;
        }

        return $payload;
    }
}

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

final readonly class Text
{
    public function __construct(
        private string $channel,
        private string $text,
        private ?string $threadTs = null,
        private bool $unfurlLinks = true,
    ) {}

    public function toArray(): array
    {
        $payload = [
            'channel' => $this->channel,
            'text' => $this->text,
            'unfurl_links' => $this->unfurlLinks,
        ];

        if ($this->threadTs !== null) {
            $payload['thread_ts'] = $this->threadTs;
        }

        return $payload;
    }
}

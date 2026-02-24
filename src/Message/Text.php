<?php

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

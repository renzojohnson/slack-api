<?php

declare(strict_types=1);

namespace RenzoJohnson\Slack\Message;

final readonly class Block
{
    /**
     * @param array<array{type: string, text?: array, elements?: array}> $blocks
     */
    public function __construct(
        private string $channel,
        private array $blocks,
        private ?string $text = null,
        private ?string $threadTs = null,
    ) {}

    public function toArray(): array
    {
        $payload = [
            'channel' => $this->channel,
            'blocks' => $this->blocks,
        ];

        if ($this->text !== null) {
            $payload['text'] = $this->text;
        }

        if ($this->threadTs !== null) {
            $payload['thread_ts'] = $this->threadTs;
        }

        return $payload;
    }

    public static function section(string $text, string $type = 'mrkdwn'): array
    {
        return [
            'type' => 'section',
            'text' => [
                'type' => $type,
                'text' => $text,
            ],
        ];
    }

    public static function divider(): array
    {
        return ['type' => 'divider'];
    }

    public static function header(string $text): array
    {
        return [
            'type' => 'header',
            'text' => [
                'type' => 'plain_text',
                'text' => $text,
            ],
        ];
    }

    public static function image(string $url, string $altText, ?string $title = null): array
    {
        $block = [
            'type' => 'image',
            'image_url' => $url,
            'alt_text' => $altText,
        ];

        if ($title !== null) {
            $block['title'] = [
                'type' => 'plain_text',
                'text' => $title,
            ];
        }

        return $block;
    }
}

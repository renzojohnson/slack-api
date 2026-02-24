<?php

declare(strict_types=1);

namespace RenzoJohnson\Slack\Message;

final readonly class Attachment
{
    /**
     * @param array<array{title?: string, value?: string, short?: bool}> $fields
     */
    public function __construct(
        private string $channel,
        private string $fallback,
        private ?string $color = null,
        private ?string $pretext = null,
        private ?string $title = null,
        private ?string $titleLink = null,
        private ?string $text = null,
        private array $fields = [],
        private ?string $footer = null,
        private ?int $ts = null,
    ) {}

    public function toArray(): array
    {
        $attachment = [
            'fallback' => $this->fallback,
        ];

        if ($this->color !== null) {
            $attachment['color'] = $this->color;
        }

        if ($this->pretext !== null) {
            $attachment['pretext'] = $this->pretext;
        }

        if ($this->title !== null) {
            $attachment['title'] = $this->title;
        }

        if ($this->titleLink !== null) {
            $attachment['title_link'] = $this->titleLink;
        }

        if ($this->text !== null) {
            $attachment['text'] = $this->text;
        }

        if ($this->fields !== []) {
            $attachment['fields'] = $this->fields;
        }

        if ($this->footer !== null) {
            $attachment['footer'] = $this->footer;
        }

        if ($this->ts !== null) {
            $attachment['ts'] = $this->ts;
        }

        return [
            'channel' => $this->channel,
            'attachments' => [$attachment],
        ];
    }
}

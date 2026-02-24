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

namespace RenzoJohnson\Slack;

use RenzoJohnson\Slack\Http\Client;
use RenzoJohnson\Slack\Message\Attachment;
use RenzoJohnson\Slack\Message\Block;
use RenzoJohnson\Slack\Message\Text;

final class Slack
{
    private Client $client;

    public function __construct(
        string $botToken,
        int $timeout = 30,
    ) {
        $this->client = new Client($botToken, $timeout);
    }

    public function sendText(string $channel, string $text, ?string $threadTs = null): array
    {
        $message = new Text($channel, $text, $threadTs);

        return $this->client->post('chat.postMessage', $message->toArray());
    }

    /**
     * @param array<array{type: string, text?: array, elements?: array}> $blocks
     */
    public function sendBlocks(string $channel, array $blocks, ?string $text = null, ?string $threadTs = null): array
    {
        $message = new Block($channel, $blocks, $text, $threadTs);

        return $this->client->post('chat.postMessage', $message->toArray());
    }

    public function sendAttachment(
        string $channel,
        string $fallback,
        ?string $color = null,
        ?string $title = null,
        ?string $text = null,
        array $fields = [],
    ): array {
        $message = new Attachment($channel, $fallback, $color, title: $title, text: $text, fields: $fields);

        return $this->client->post('chat.postMessage', $message->toArray());
    }

    public function updateMessage(string $channel, string $ts, string $text): array
    {
        return $this->client->post('chat.update', [
            'channel' => $channel,
            'ts' => $ts,
            'text' => $text,
        ]);
    }

    public function deleteMessage(string $channel, string $ts): array
    {
        return $this->client->post('chat.delete', [
            'channel' => $channel,
            'ts' => $ts,
        ]);
    }

    public function addReaction(string $channel, string $timestamp, string $name): array
    {
        return $this->client->post('reactions.add', [
            'channel' => $channel,
            'timestamp' => $timestamp,
            'name' => $name,
        ]);
    }

    public function listConversations(array $types = ['public_channel'], int $limit = 100): array
    {
        return $this->client->get('conversations.list', [
            'types' => implode(',', $types),
            'limit' => $limit,
        ]);
    }

    public function getConversationHistory(string $channel, int $limit = 100): array
    {
        return $this->client->get('conversations.history', [
            'channel' => $channel,
            'limit' => $limit,
        ]);
    }

    public function authTest(): array
    {
        return $this->client->post('auth.test', []);
    }
}

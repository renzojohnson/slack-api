<?php

declare(strict_types=1);

namespace RenzoJohnson\Slack\Webhook;

use RenzoJohnson\Slack\Exception\SlackException;

final class SlashCommand
{
    public static function verify(string $signingSecret, int $toleranceSeconds = 300): array
    {
        $timestamp = $_SERVER['HTTP_X_SLACK_REQUEST_TIMESTAMP'] ?? '';
        $signature = $_SERVER['HTTP_X_SLACK_SIGNATURE'] ?? '';
        $body = file_get_contents('php://input');

        if ($body === false || $body === '') {
            throw new SlackException('Empty request body');
        }

        if ($timestamp === '' || $signature === '') {
            throw new SlackException('Missing Slack signature headers');
        }

        if (abs(time() - (int) $timestamp) > $toleranceSeconds) {
            throw new SlackException('Request timestamp too old');
        }

        if (!self::validateSignature($body, $timestamp, $signature, $signingSecret)) {
            throw new SlackException('Invalid signature');
        }

        parse_str($body, $params);

        return $params;
    }

    public static function validateSignature(
        string $body,
        string $timestamp,
        string $signature,
        string $signingSecret,
    ): bool {
        if ($signature === '' || $timestamp === '') {
            return false;
        }

        $baseString = 'v0:' . $timestamp . ':' . $body;
        $expected = 'v0=' . hash_hmac('sha256', $baseString, $signingSecret);

        return hash_equals($expected, $signature);
    }

    public static function respond(string $text, bool $inChannel = false): never
    {
        header('Content-Type: application/json');

        echo json_encode([
            'response_type' => $inChannel ? 'in_channel' : 'ephemeral',
            'text' => $text,
        ], JSON_THROW_ON_ERROR);

        exit;
    }
}

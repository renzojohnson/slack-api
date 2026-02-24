# Slack API for PHP

[![Latest Version](https://img.shields.io/packagist/v/renzojohnson/slack-api.svg)](https://packagist.org/packages/renzojohnson/slack-api)
[![PHP Version](https://img.shields.io/packagist/php-v/renzojohnson/slack-api.svg)](https://packagist.org/packages/renzojohnson/slack-api)
[![License](https://img.shields.io/packagist/l/renzojohnson/slack-api.svg)](https://github.com/renzojohnson/slack-api/blob/main/LICENSE)

Lightweight PHP wrapper for [Slack Web API](https://api.slack.com/web). Zero dependencies.

Send messages, blocks, and attachments to channels. Incoming webhooks and slash command verification with HMAC-SHA256.

**Author:** [Renzo Johnson](https://renzojohnson.com)

## Requirements

- PHP 8.4+
- ext-curl
- ext-json

## Installation

```bash
composer require renzojohnson/slack-api
```

## Quick Start

```php
use RenzoJohnson\Slack\Slack;

$slack = new Slack('xoxb-your-bot-token');

// Send a text message
$slack->sendText('#general', 'Hello from PHP!');
```

## Web API Methods

### Send Text

```php
$slack->sendText('#general', 'Hello World');

// Thread reply
$slack->sendText('#general', 'Replying...', threadTs: '1234567890.123456');
```

### Send Blocks (Block Kit)

```php
use RenzoJohnson\Slack\Message\Block;

$slack->sendBlocks('#alerts', [
    Block::header('Deploy Alert'),
    Block::divider(),
    Block::section('Production deploy completed for *v2.1.0*'),
    Block::image('https://example.com/chart.png', 'CPU chart'),
], text: 'Deploy Alert — fallback for notifications');
```

### Send Attachment (Legacy)

```php
$slack->sendAttachment(
    '#ops',
    fallback: 'Server CPU at 95%',
    color: '#ff0000',
    title: 'Server Alert',
    text: 'CPU usage has exceeded threshold.',
    fields: [
        ['title' => 'Server', 'value' => 'web-01', 'short' => true],
        ['title' => 'CPU', 'value' => '95%', 'short' => true],
    ],
);
```

### Update Message

```php
$response = $slack->sendText('#general', 'Loading...');
$ts = $response['ts'];

$slack->updateMessage('#general', $ts, 'Done!');
```

### Delete Message

```php
$slack->deleteMessage('#general', '1234567890.123456');
```

### Add Reaction

```php
$slack->addReaction('#general', '1234567890.123456', 'thumbsup');
```

### List Conversations

```php
$channels = $slack->listConversations();
```

### Auth Test

```php
$info = $slack->authTest();
echo $info['user']; // bot username
```

## Incoming Webhooks

No bot token needed — just a webhook URL.

```php
use RenzoJohnson\Slack\Webhook\IncomingWebhook;

$webhook = new IncomingWebhook('https://hooks.slack.com/services/T.../B.../xxx');

// Simple text
$webhook->send('Deploy complete!');

// Rich message
use RenzoJohnson\Slack\Message\WebhookMessage;

$webhook->send(new WebhookMessage(
    'Deploy complete!',
    username: 'DeployBot',
    iconEmoji: ':rocket:',
));
```

## Slash Commands

### Verify and Parse

```php
use RenzoJohnson\Slack\Webhook\SlashCommand;

// Verifies X-Slack-Signature via HMAC-SHA256, returns parsed params
$params = SlashCommand::verify('your-signing-secret');

echo $params['command'];    // /weather
echo $params['text'];       // 94070
echo $params['user_name'];  // john
```

### Respond

```php
// Ephemeral (only visible to user)
SlashCommand::respond('Processing your request...');

// Visible to entire channel
SlashCommand::respond('Weather: 72F, Sunny', inChannel: true);
```

## Error Handling

```php
use RenzoJohnson\Slack\Exception\AuthenticationException;
use RenzoJohnson\Slack\Exception\RateLimitException;
use RenzoJohnson\Slack\Exception\SlackException;

try {
    $slack->sendText('#general', 'Hello');
} catch (AuthenticationException $e) {
    // Invalid bot token
} catch (RateLimitException $e) {
    $retryAfter = $e->getRetryAfter(); // seconds
} catch (SlackException $e) {
    // Other API errors (channel_not_found, etc.)
    $errorData = $e->getErrorData();
}
```

## Testing

```bash
composer install
vendor/bin/phpunit
```

## Links

- [Packagist](https://packagist.org/packages/renzojohnson/slack-api)
- [GitHub](https://github.com/renzojohnson/slack-api)
- [Issues](https://github.com/renzojohnson/slack-api/issues)
- [Author](https://renzojohnson.com)

## License

MIT License. Copyright (c) 2026 [Renzo Johnson](https://renzojohnson.com).

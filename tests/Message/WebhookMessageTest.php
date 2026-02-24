<?php

declare(strict_types=1);

namespace RenzoJohnson\Slack\Tests\Message;

use PHPUnit\Framework\TestCase;
use RenzoJohnson\Slack\Message\WebhookMessage;

final class WebhookMessageTest extends TestCase
{
    public function testSimpleMessage(): void
    {
        $message = new WebhookMessage('Hello from webhook');
        $result = $message->toArray();

        $this->assertSame('Hello from webhook', $result['text']);
        $this->assertArrayNotHasKey('blocks', $result);
        $this->assertArrayNotHasKey('username', $result);
    }

    public function testMessageWithOverrides(): void
    {
        $message = new WebhookMessage(
            'Alert!',
            username: 'DeployBot',
            iconEmoji: ':rocket:',
            channel: '#deploys',
        );
        $result = $message->toArray();

        $this->assertSame('DeployBot', $result['username']);
        $this->assertSame(':rocket:', $result['icon_emoji']);
        $this->assertSame('#deploys', $result['channel']);
    }
}

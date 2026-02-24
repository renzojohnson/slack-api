<?php

declare(strict_types=1);

namespace RenzoJohnson\Slack\Tests\Message;

use PHPUnit\Framework\TestCase;
use RenzoJohnson\Slack\Message\Text;

final class TextTest extends TestCase
{
    public function testToArrayReturnsCorrectStructure(): void
    {
        $message = new Text('#general', 'Hello World');
        $result = $message->toArray();

        $this->assertSame('#general', $result['channel']);
        $this->assertSame('Hello World', $result['text']);
        $this->assertTrue($result['unfurl_links']);
        $this->assertArrayNotHasKey('thread_ts', $result);
    }

    public function testThreadedMessage(): void
    {
        $message = new Text('#general', 'Reply', threadTs: '1234567890.123456');
        $result = $message->toArray();

        $this->assertSame('1234567890.123456', $result['thread_ts']);
    }
}

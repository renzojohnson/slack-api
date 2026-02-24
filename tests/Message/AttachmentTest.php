<?php

declare(strict_types=1);

namespace RenzoJohnson\Slack\Tests\Message;

use PHPUnit\Framework\TestCase;
use RenzoJohnson\Slack\Message\Attachment;

final class AttachmentTest extends TestCase
{
    public function testBasicAttachment(): void
    {
        $message = new Attachment(
            '#general',
            'Deploy notification',
            color: '#36a64f',
            title: 'Deployed v1.0',
            text: 'All systems go.',
        );
        $result = $message->toArray();

        $this->assertSame('#general', $result['channel']);
        $this->assertCount(1, $result['attachments']);
        $this->assertSame('#36a64f', $result['attachments'][0]['color']);
        $this->assertSame('Deployed v1.0', $result['attachments'][0]['title']);
        $this->assertSame('All systems go.', $result['attachments'][0]['text']);
    }

    public function testAttachmentWithFields(): void
    {
        $message = new Attachment(
            '#ops',
            'Server alert',
            fields: [
                ['title' => 'CPU', 'value' => '95%', 'short' => true],
                ['title' => 'Memory', 'value' => '82%', 'short' => true],
            ],
        );
        $result = $message->toArray();

        $this->assertCount(2, $result['attachments'][0]['fields']);
    }
}

<?php

declare(strict_types=1);

namespace RenzoJohnson\Slack\Tests\Message;

use PHPUnit\Framework\TestCase;
use RenzoJohnson\Slack\Message\Block;

final class BlockTest extends TestCase
{
    public function testToArrayWithBlocks(): void
    {
        $blocks = [
            Block::header('Alert'),
            Block::divider(),
            Block::section('Something happened'),
        ];

        $message = new Block('#alerts', $blocks, text: 'Fallback text');
        $result = $message->toArray();

        $this->assertSame('#alerts', $result['channel']);
        $this->assertCount(3, $result['blocks']);
        $this->assertSame('Fallback text', $result['text']);
    }

    public function testSectionHelper(): void
    {
        $block = Block::section('Hello *world*');

        $this->assertSame('section', $block['type']);
        $this->assertSame('mrkdwn', $block['text']['type']);
        $this->assertSame('Hello *world*', $block['text']['text']);
    }

    public function testDividerHelper(): void
    {
        $block = Block::divider();

        $this->assertSame('divider', $block['type']);
    }

    public function testHeaderHelper(): void
    {
        $block = Block::header('My Header');

        $this->assertSame('header', $block['type']);
        $this->assertSame('plain_text', $block['text']['type']);
        $this->assertSame('My Header', $block['text']['text']);
    }

    public function testImageHelper(): void
    {
        $block = Block::image('https://example.com/img.png', 'An image', 'Title');

        $this->assertSame('image', $block['type']);
        $this->assertSame('https://example.com/img.png', $block['image_url']);
        $this->assertSame('An image', $block['alt_text']);
        $this->assertSame('Title', $block['title']['text']);
    }
}

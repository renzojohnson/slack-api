<?php

declare(strict_types=1);

namespace RenzoJohnson\Slack\Tests;

use PHPUnit\Framework\TestCase;
use RenzoJohnson\Slack\Slack;

final class SlackTest extends TestCase
{
    public function testInstantiation(): void
    {
        $slack = new Slack('xoxb-test-token');

        $this->assertInstanceOf(Slack::class, $slack);
    }
}

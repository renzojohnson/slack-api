<?php

declare(strict_types=1);

namespace RenzoJohnson\Slack\Tests\Webhook;

use PHPUnit\Framework\TestCase;
use RenzoJohnson\Slack\Webhook\SlashCommand;

final class SlashCommandTest extends TestCase
{
    public function testValidateSignatureValid(): void
    {
        $secret = 'my_signing_secret';
        $timestamp = '1531420618';
        $body = 'token=xyzz0WbapA4vBCDEFasx0YCo&command=/weather&text=94070';

        $baseString = 'v0:' . $timestamp . ':' . $body;
        $signature = 'v0=' . hash_hmac('sha256', $baseString, $secret);

        $this->assertTrue(SlashCommand::validateSignature($body, $timestamp, $signature, $secret));
    }

    public function testValidateSignatureInvalid(): void
    {
        $this->assertFalse(SlashCommand::validateSignature('body', '123', 'v0=bad', 'secret'));
    }

    public function testValidateSignatureEmptyInputs(): void
    {
        $this->assertFalse(SlashCommand::validateSignature('body', '', 'sig', 'secret'));
        $this->assertFalse(SlashCommand::validateSignature('body', '123', '', 'secret'));
    }
}

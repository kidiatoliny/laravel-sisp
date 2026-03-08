<?php

declare(strict_types=1);

use Akira\Sisp\Models\Transaction;

it('renders blade views without a host layouts.app view', function (): void {
    config()->set('view.paths', []);

    $paymentFormHtml = view('sisp::payment-form', [
        'formAction' => 'https://example.test/pay',
        'fields' => ['token' => 'abc123'],
    ])->render();

    expect($paymentFormHtml)
        ->toContain('<!DOCTYPE html>')
        ->and($paymentFormHtml)->toContain('sisp-payment-form');

    $transaction = Transaction::factory()->pending()->create();

    $paymentResponseHtml = view('sisp::payment-response', [
        'transaction' => $transaction,
        'payload' => [],
        'error' => null,
        'allowRetry' => false,
        'invoice' => null,
    ])->render();

    expect($paymentResponseHtml)->toContain((string) __('sisp::messages.payment.response.pending_title'));
});

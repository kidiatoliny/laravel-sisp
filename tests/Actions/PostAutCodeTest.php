<?php

declare(strict_types=1);

use Akira\Sisp\Actions\PostAutCode;

it('generates base64 sha512 of posAutCode', function (): void {
    $action = resolve(PostAutCode::class);
    $expected = base64_encode(hash('sha512', (string) config('sisp.posAutCode'), true));

    expect($action->handle())->toBe($expected);
});

it('memoizes the hash for the action instance', function (): void {
    config()->set('sisp.posAutCode', 'first-code');
    $action = resolve(PostAutCode::class);

    $firstHash = $action->handle();

    config()->set('sisp.posAutCode', 'second-code');

    expect($action->handle())->toBe($firstHash);
});

it('uses fresh config when resolving a new action instance', function (): void {
    config()->set('sisp.posAutCode', 'first-code');
    $firstAction = resolve(PostAutCode::class);

    config()->set('sisp.posAutCode', 'second-code');
    $secondAction = resolve(PostAutCode::class);

    expect($secondAction->handle())->not->toBe($firstAction->handle());
});

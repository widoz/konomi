<?php

declare(strict_types=1);

namespace SpaghettiDojo\Konomi\Tests\Unit\Post;

use SpaghettiDojo\Konomi\Post\StorageKey;
use SpaghettiDojo\Konomi\User\ItemGroup;

describe('new', function (): void {
    it('creates a new instance with a valid base key', function (): void {
        $base = 'test_base';
        $storageKey = StorageKey::new($base);
        expect($storageKey)->toBeInstanceOf(StorageKey::class);
    });

    it('throws an assertion error when base key is empty', function (): void {
        try {
            StorageKey::new('');
        } catch (\Throwable $thr) {
            expect($thr)->toBeInstanceOf(\AssertionError::class);
        }
    });
});

describe('for', function (): void {
    it('generates a valid storage key for REACTION group', function (): void {
        $base = 'test_base';
        $storageKey = StorageKey::new($base);
        $key = $storageKey->for(ItemGroup::REACTION);
        expect($key)->toBe('test_base.reaction');
    });

    it('generates a valid storage key for BOOKMARK group', function (): void {
        $base = 'test_base';
        $storageKey = StorageKey::new($base);
        $key = $storageKey->for(ItemGroup::BOOKMARK);
        expect($key)->toBe('test_base.bookmark');
    });

    it('sanitizes the key by removing non-alphanumeric characters', function (): void {
        $base = 'test-base!@#';
        $storageKey = StorageKey::new($base);
        $key = $storageKey->for(ItemGroup::REACTION);
        expect($key)->toBe('testbase.reaction');
    });

    it('throws a RuntimeException when key is empty after sanitization', function (): void {
        $base = '!@#$%^&*()';
        $storageKey = StorageKey::new($base);
        \Brain\Monkey\Functions\expect('preg_replace')->once()->andReturn('');
        expect(fn () => $storageKey->for(ItemGroup::REACTION))->toThrow(\RuntimeException::class, 'Storage key cannot be empty after sanitization');
    });
});

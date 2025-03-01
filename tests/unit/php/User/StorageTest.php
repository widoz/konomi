<?php

declare(strict_types=1);

namespace Widoz\Wp\Konomi\Tests\Unit\User;

use Brain\Monkey\Functions;
use Widoz\Wp\Konomi\User;

beforeEach(function (): void {
    $this->storage = User\Storage::new();
});

describe('Storage', function (): void {
    it('returns empty array for invalid ID', function (): void {
        expect($this->storage->read(0, 'test_key'))->toBe([]);
    });

    it('returns empty array for empty key', function (): void {
        expect($this->storage->read(1, ''))->toBe([]);
    });

    it('returns array when meta value is an array', function (): void {
        $expected = ['item1', 'item2'];

        Functions\expect('get_user_meta')
            ->once()
            ->with(1, 'likes', true)
            ->andReturn($expected);

        expect($this->storage->read(1, 'likes'))->toBe($expected);
    });

    it('returns empty array when meta value is not an array', function (): void {
        Functions\expect('get_user_meta')
            ->once()
            ->with(1, 'likes', true)
            ->andReturn('string_value');

        expect($this->storage->read(1, 'likes'))->toBe([]);
    });

    it('returns false for invalid ID', function (): void {
        expect($this->storage->write(0, 'test_key', []))->toBeFalse();
    });

    it('returns false for empty key', function (): void {
        expect($this->storage->write(1, '', []))->toBeFalse();
    });

    it('returns true when update is successful', function (): void {
        Functions\expect('update_user_meta')
            ->once()
            ->with(1, 'likes', ['item1', 'item2'])
            ->andReturn(1);

        expect($this->storage->write(1, 'likes', ['item1', 'item2']))->toBeTrue();
    });

    it('returns false when update fails', function (): void {
        Functions\expect('update_user_meta')
            ->once()
            ->with(1, 'likes', [])
            ->andReturn(false);

        expect($this->storage->write(1, 'likes', []))->toBeFalse();
    });
});

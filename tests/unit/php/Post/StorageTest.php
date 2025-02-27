<?php

declare(strict_types=1);

namespace Widoz\Wp\Konomi\Tests\Unit\Post;

use Brain\Monkey\Functions;
use Widoz\Wp\Konomi\Post\Storage;

describe('Storage', function () {
    it('read data from the storage', function (): void {
        $storage = Storage::new();
        Functions\expect('get_post_meta')->once()->with(1, 'key', true)->andReturn(['data']);
        $data = $storage->read(1, 'key');
        expect($data)->toBe(['data']);
    });

    it('write data to the storage', function (): void {
        $storage = Storage::new();
        Functions\expect('update_post_meta')->once()->with(1, 'key', ['data'])->andReturn(true);
        $result = $storage->write(1, 'key', ['data']);
        expect($result)->toBeTrue();
    });

    it('read returns empty array when id is invalid', function (): void {
        $storage = Storage::new();
        Functions\expect('get_post_meta')->never();
        expect($storage->read(0, 'key'))->toBe([]);
        expect($storage->read(-1, 'key'))->toBe([]);
    });

    it('read returns empty array when key is empty', function (): void {
        $storage = Storage::new();
        Functions\expect('get_post_meta')->never();
        expect($storage->read(1, ''))->toBe([]);
    });

    it('read returns empty array when result is not an array', function (): void {
        $storage = Storage::new();
        Functions\expect('get_post_meta')->once()->with(1, 'key', true)->andReturn('not-an-array');
        expect($storage->read(1, 'key'))->toBe([]);
    });

    it('write returns false when id is invalid', function (): void {
        $storage = Storage::new();
        Functions\expect('update_post_meta')->never();
        expect($storage->write(0, 'key', ['data']))->toBeFalse();
        expect($storage->write(-1, 'key', ['data']))->toBeFalse();
    });

    it('write returns false when key is empty', function (): void {
        $storage = Storage::new();
        Functions\expect('update_post_meta')->never();
        expect($storage->write(1, '', ['data']))->toBeFalse();
    });
});

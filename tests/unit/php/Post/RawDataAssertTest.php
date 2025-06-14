<?php

declare(strict_types=1);

namespace SpaghettiDojo\Konomi\Tests\Unit\Post;

use SpaghettiDojo\Konomi\Post\RawDataAssert;

beforeEach(function (): void {
    $this->rawDataAsserter = RawDataAssert::new();
});

describe('ensureDataStructure', function (): void {
    it('validates correct data structure', function (): void {
        $data = [
            1 => [[1, 'post']],
            2 => [[2, 'page'], [3, 'post']],
        ];

        $result = iterator_to_array($this->rawDataAsserter->ensureDataStructure($data));

        expect($result)->toBe($data);
    });

    it('filters out invalid user IDs', function (): void {
        $data = [
            0 => [[1, 'post']],
            -1 => [[2, 'page']],
            'string' => [[3, 'post']],
            1 => [[4, 'page']],
        ];

        $result = iterator_to_array($this->rawDataAsserter->ensureDataStructure($data));

        expect($result)->toBe([1 => [[4, 'page']]]);
    });

    it('filters out invalid raw items structure', function (): void {
        $data = [
            1 => 'not an array',
            2 => [],
            3 => [[1, 'post']],
        ];

        $result = iterator_to_array($this->rawDataAsserter->ensureDataStructure($data));

        expect($result)->toBe([3 => [[1, 'post']]]);
    });

    it('filters out invalid item format', function (): void {
        $data = [
            1 => [[0, 'post']],
            2 => [['1', 'post']],
            3 => [[1, '']],
            4 => [[1, 123]],
            5 => [[1]],
            6 => [[1, 'post', 'extra']],
            7 => [[1, 'post']],
        ];

        $result = iterator_to_array($this->rawDataAsserter->ensureDataStructure($data));

        expect($result)->toBe([7 => [[1, 'post']]]);
    });

    it('handles empty input array', function (): void {
        $result = iterator_to_array($this->rawDataAsserter->ensureDataStructure([]));

        expect($result)->toBe([]);
    });
});

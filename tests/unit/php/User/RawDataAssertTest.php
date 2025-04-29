<?php

declare(strict_types=1);

namespace Widoz\Wp\Konomi\Tests\Unit\User;

use Widoz\Wp\Konomi\User\RawDataAssert;

beforeEach(function (): void {
    $this->rawDataAsserter = RawDataAssert::new();
});

describe('User Stored Data Validator', function (): void {
    describe('ensureDataStructure', function (): void {
        it('validates correct data structure', function (): void {
            $data = [
                [1, 'post'],
                [2, 'page'],
                [3, 'video'],
            ];

            $result = iterator_to_array($this->rawDataAsserter->ensureDataStructure($data));

            expect($result)->toBe($data);
        });

        it('filters out invalid raw entities', function (): void {
            $data = [
                ['0', 'post'],
                [-1, 'video'],
                ['abc', 'product'],
                [2, 'page'],
            ];

            $result = iterator_to_array($this->rawDataAsserter->ensureDataStructure($data));

            expect(count($result))->toBe(1);
            expect($result)->toBe([[2, 'page']]);
        });

        it('filters out invalid raw items structure', function (): void {
            $data = [
                null,
                'string',
                [],
                [1, 'administrator'],
                (object) ['id' => 1],
            ];

            $result = iterator_to_array($this->rawDataAsserter->ensureDataStructure($data));

            expect($result)->toBe([[1, 'administrator']]);
        });

        it('filters out invalid item format', function (): void {
            $data = [
                [1, ''],
                [1, null],
                [1],
                [1, 'author', 'extra'],
                [2, 'editor'],
                [3, 123],
            ];

            $result = iterator_to_array($this->rawDataAsserter->ensureDataStructure($data));

            expect($result)->toBe([[2, 'editor']]);
        });

        it('handles empty input array', function (): void {
            $result = iterator_to_array($this->rawDataAsserter->ensureDataStructure([]));

            expect($result)->toBe([]);
        });
    });
});

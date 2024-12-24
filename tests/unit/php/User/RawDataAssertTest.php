<?php

declare(strict_types=1);

use Widoz\Wp\Konomi\User\RawDataAssert;

beforeEach(function () {
    $this->rawDataAsserter = RawDataAssert::new();
});

describe('User Stored Data Validator', function () {
    it('validates correct data structure', function () {
        $data = [
            [1, 'post'],
            [2, 'page'],
            [3, 'video']
        ];

        $result = iterator_to_array($this->rawDataAsserter->ensureDataStructure($data));

        expect($result)->toBe($data);
    });

    it('filters out invalid raw entities', function () {
        $data = [
            ['0', 'post'],
            [-1, 'video'],
            ['abc', 'product'],
            [2, 'page']
        ];

        $result = iterator_to_array($this->rawDataAsserter->ensureDataStructure($data));

        expect(count($result))->toBe(1);
        expect($result)->toBe([[2, 'page']]);
    });

    it('filters out invalid raw items structure', function () {
        $data = [
            null,
            'string',
            [],
            [1, 'administrator'],
            (object)['id' => 1]
        ];

        $result = iterator_to_array($this->rawDataAsserter->ensureDataStructure($data));

        expect($result)->toBe([[1, 'administrator']]);
    });

    it('filters out invalid item format', function () {
        $data = [
            [1, ''],
            [1, null],
            [1],
            [1, 'author', 'extra'],
            [2, 'editor'],
            [3, 123]
        ];

        $result = iterator_to_array($this->rawDataAsserter->ensureDataStructure($data));

        expect($result)->toBe([[2, 'editor']]);
    });

    it('handles empty input array', function () {
        $result = iterator_to_array($this->rawDataAsserter->ensureDataStructure([]));

        expect($result)->toBe([]);
    });
});

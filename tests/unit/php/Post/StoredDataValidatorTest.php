<?php

declare(strict_types=1);

use Widoz\Wp\Konomi\Post\StoredDataValidator;

beforeEach(function () {
    $this->validator = StoredDataValidator::new();
});

describe('Stored Data Validator', function () {
    it('validates correct data structure', function () {
        $data = [
            1 => [[1, 'post']],
            2 => [[2, 'page'], [3, 'post']]
        ];

        $result = iterator_to_array($this->validator->ensureDataStructure($data));

        expect($result)->toBe($data);
    });

    it('filters out invalid user IDs', function () {
        $data = [
            0 => [[1, 'post']],
            -1 => [[2, 'page']],
            'string' => [[3, 'post']],
            1 => [[4, 'page']]
        ];

        $result = iterator_to_array($this->validator->ensureDataStructure($data));

        expect($result)->toBe([1 => [[4, 'page']]]);
    });

    it('filters out invalid raw items structure', function () {
        $data = [
            1 => 'not an array',
            2 => [],
            3 => [[1, 'post']]
        ];

        $result = iterator_to_array($this->validator->ensureDataStructure($data));

        expect($result)->toBe([3 => [[1, 'post']]]);
    });

    it('filters out invalid item format', function () {
        $data = [
            1 => [[0, 'post']],
            2 => [['1', 'post']],
            3 => [[1, '']],
            4 => [[1, 123]],
            5 => [[1]],
            6 => [[1, 'post', 'extra']],
            7 => [[1, 'post']]
        ];

        $result = iterator_to_array($this->validator->ensureDataStructure($data));

        expect($result)->toBe([7 => [[1, 'post']]]);
    });

    it('handles empty input array', function () {
        $result = iterator_to_array($this->validator->ensureDataStructure([]));

        expect($result)->toBe([]);
    });
});

<?php

declare(strict_types=1);

use Widoz\Wp\Konomi\User\NullItem;

beforeEach(function (): void {
    $this->nullItem = NullItem::new();
});

describe('Null Item', function (): void {
    it('returns canned values', function (): void {
        expect($this->nullItem->id())->toBe(0);
        expect($this->nullItem->type())->toBe('');
        expect($this->nullItem->isActive())->toBeFalse();
        expect($this->nullItem->isValid())->toBeFalse();
    });
});

<?php

declare(strict_types=1);

use Brain\Monkey\Functions;
use Widoz\Wp\Konomi\Blocks\BlockRegistrar;

describe('Block Registrar', function (): void {
    it('register all blocks in the blocks directory', function (): void {
        $blocksDirectory = fixturesDirectory() . '/blocks';
        $blockRegistrar = BlockRegistrar::new($blocksDirectory);

        Functions\expect('register_block_type_from_metadata')->twice();

        Functions\expect('register_block_type_from_metadata')
            ->with($blocksDirectory . '/block-one')
            ->andAlsoExpectIt()
            ->with($blocksDirectory . '/block-two');

        $blockRegistrar->registerBlockTypes();
    });

    it('throws an exception when the blocks directory is empty', function (): void {
        expect(function () {
            BlockRegistrar::new('');
        })->toThrow('InvalidArgumentException');
    });
});

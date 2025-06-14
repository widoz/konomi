<?php

declare(strict_types=1);

namespace SpaghettiDojo\Konomi\Tests\Unit\Blocks;

use Brain\Monkey\Functions;
use SpaghettiDojo\Konomi\Blocks\BlockRegistrar;

describe('registerBlockTypes', function (): void {
    it('register all blocks in the blocks directory', function (): void {
        $blocksDirectory = fixturesDirectory() . '/blocks';
        $blockManifestPath = $blocksDirectory . '/blocks-manifest.php';
        $blockRegistrar = BlockRegistrar::new($blocksDirectory, $blockManifestPath);

        Functions\expect('register_block_type_from_metadata')->twice();
        Functions\expect('wp_register_block_metadata_collection')
            ->once()
            ->with($blocksDirectory, $blockManifestPath);

        Functions\expect('register_block_type_from_metadata')
            ->with($blocksDirectory . '/block-one')
            ->andAlsoExpectIt()
            ->with($blocksDirectory . '/block-two');

        $blockRegistrar->registerBlockTypes();
    });

    it('throws an exception when the blocks directory or the blocks manifest is empty', function (): void {
        expect(function (): void {
            BlockRegistrar::new('', '');
        })->toThrow('InvalidArgumentException');
    });
});

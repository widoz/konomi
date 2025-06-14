<?php

declare(strict_types=1);

namespace SpaghettiDojo\Konomi\Blocks;

/**
 * @internal
 */
class BlockRegistrar
{
    /**
     * @throws \InvalidArgumentException
     */
    public static function new(string $blocksDirectory, string $blocksManifestPath): self
    {
        return new self($blocksDirectory, $blocksManifestPath);
    }

    /**
     * @throws \InvalidArgumentException
     */
    final private function __construct(
        private readonly string $blocksDirectory,
        private readonly string $blocksManifestPath
    ) {

        $this->blocksDirectory or throw new \InvalidArgumentException(
            'Blocks directory must be a non-empty string'
        );
        $this->blocksManifestPath or throw new \InvalidArgumentException(
            'Blocks manifest path must be a non-empty string'
        );
    }

    public function registerBlockTypes(): void
    {
        $blocksDirectory = untrailingslashit($this->blocksDirectory);
        $blockPaths = new \DirectoryIterator($blocksDirectory);

        wp_register_block_metadata_collection(
            $blocksDirectory,
            $this->blocksManifestPath
        );

        foreach ($blockPaths as $blockPath) {
            if ($blockPath->isDot() || $blockPath->isFile()) {
                continue;
            }

            $blockJson = $blockPath->getPathname() . '/block.json';
            if (!file_exists($blockJson)) {
                continue;
            }
            register_block_type_from_metadata($blockPath->getPathname());
        }
    }
}

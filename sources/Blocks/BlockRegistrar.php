<?php

declare(strict_types=1);

namespace Widoz\Wp\Konomi\Blocks;

class BlockRegistrar
{
    public static function new(string $blocksDirectory): self
    {
        return new self($blocksDirectory);
    }

    final private function __construct(private readonly string $blocksDirectory)
    {
        $this->blocksDirectory or throw new \InvalidArgumentException(
            'Blocks directory must be a non-empty string'
        );
    }

    public function registerBlockTypes(): void
    {
        $blocksDirectory = untrailingslashit($this->blocksDirectory);
        $blockPaths = new \DirectoryIterator($blocksDirectory);

        foreach ($blockPaths as $blockPath) {
            if ($blockPath->isDot() || $blockPath->isFile()) {
                continue;
            }

            register_block_type_from_metadata($blockPath->getPathname());
        }
    }
}

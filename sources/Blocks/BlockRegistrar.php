<?php

declare(strict_types=1);

namespace Widoz\Wp\Konomi\Blocks;

use Widoz\Wp\Konomi\Utils;

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

        foreach ($blockPaths as $blockName) {
            if ($blockName->isDot() || $blockName->isFile()) {
                continue;
            }

            $this->enqueueBlockDependenciesScripts($blockName);
            register_block_type_from_metadata($blockName->getPathname());
        }
    }

    private function enqueueBlockDependenciesScripts(\DirectoryIterator $blockDirectory): void
    {
        $dependencies = [];
        $file = "{$blockDirectory->getPathname()}/scripts-dependencies.php";
        is_readable($file) and $dependencies = include_once "{$blockDirectory->getPathname()}/scripts-dependencies.php";

        if (!$dependencies) {
            return;
        }

        Utils\DeferTaskAtBlockRendering::do(
            function () use ($dependencies): void {
                array_walk($dependencies, 'wp_enqueue_script');
            }
        );
    }
}

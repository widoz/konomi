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

        foreach ($blockPaths as $blockName) {
            if ($blockName->isDot() || $blockName->isFile()) {
                continue;
            }

            $this->enqueueBlockDependenciesScripts($blockName);
            register_block_type_from_metadata($blockName->getPathname());
        }
    }

    private function enqueueBlockDependenciesScripts(\DirectoryIterator $blockName): void
    {
        $dependencies = [];
        $file = "{$blockName->getPathname()}/scripts-dependencies.php";
        is_readable($file) and $dependencies = include_once "{$blockName->getPathname()}/scripts-dependencies.php";

        if (!$dependencies) {
            return;
        }

        add_filter(
            'pre_render_block',
            function ($nullish) use ($dependencies): mixed {
                array_walk($dependencies, 'wp_enqueue_script');
                return $nullish;
            },
        );
    }
}

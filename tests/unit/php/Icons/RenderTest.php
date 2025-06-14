<?php

declare(strict_types=1);

use SpaghettiDojo\Konomi\Configuration;
use SpaghettiDojo\Konomi\Icons;
use org\bovigo\vfs\vfsStream;
use Brain\Monkey\Functions;

beforeEach(function (): void {
    $this->svgContent = '<svg><path d="M10 10 H 90 V 90 H 10 L 10 10"></path></svg>';
    $this->fs = vfsStream::setup('root', null, [
        'icons' => [
            'icon-name.svg' => $this->svgContent,
            'non-cached-icon-name.svg' => $this->svgContent,
            'empty-icon.svg' => '',
        ],
    ]);
    $this->configuration = \Mockery::mock(Configuration\Configuration::class);
    $this->renderer = Icons\Render::new($this->configuration);
    $this->validKsesConfiguration = [
        'svg' => [
            'width' => true,
            'height' => true,
            'fill' => true,
            'class' => true,
            'viewBox' => true,
            'version' => true,
            'xmlns' => true,
            'xmlns:svg' => true,
        ],
        'path' => [
            'd' => true,
        ],
    ];
});

describe('new', function (): void {
    it('should create a new instance of Render', function (): void {
        expect($this->renderer)->toBeInstanceOf(Icons\Render::class);
    });
});

describe('render', function (): void {
    it('should return the SVG markup for a given icon name', function (): void {
        $iconName = 'icon-name';

        Functions\expect('wp_kses')
            ->with($this->svgContent, $this->validKsesConfiguration)
            ->andReturnFirstArg();

        $this->configuration->shouldReceive('iconsPath')->andReturn($this->fs->url() . '/icons');
        $result = $this->renderer->render($iconName);

        expect($result)->toBe($this->svgContent);
    });

    it('should return the cached icon', function (): void {
        $iconName = 'icon-name';

        Functions\expect('wp_kses')
            ->with($this->svgContent, $this->validKsesConfiguration)
            ->andReturnFirstArg();

        $this->configuration
            ->shouldReceive('iconsPath')
            ->andReturn($this->fs->url() . '/icons');

        $original = $this->renderer->render($iconName);
        $cached = $this->renderer->render($iconName);

        expect($cached)->toBe($original);
    });

    /*
     * Due to the Render cache, reusing an existing icon name will make the test fail if
     * the icon name has already been used because the cache will return the previously rendered icon.
     */
    it('should return empty string when wp_kses return empty string', function (): void {
        $iconName = 'non-cached-icon-name';

        Functions\expect('wp_kses')
            ->with($this->svgContent, $this->validKsesConfiguration)
            ->andReturn('');

        $this->configuration
            ->shouldReceive('iconsPath')
            ->andReturn($this->fs->url() . '/icons');

        $result = $this->renderer->render($iconName);

        expect($result)->toBe('');
    });

    it('should return empty string when the icon file does not exist', function (): void {
        $iconName = 'non-existent-icon';

        $this->configuration
            ->shouldReceive('iconsPath')
            ->andReturn($this->fs->url() . '/icons');

        Functions\expect('wp_kses')
            ->with('', $this->validKsesConfiguration)
            ->andReturn('');

        $result = $this->renderer->render($iconName);

        expect($result)->toBe('');
    });

    it('return empty string when file is empty', function (): void {
        $iconName = 'empty-icon';

        Functions\expect('wp_kses')
            ->with('', $this->validKsesConfiguration)
            ->andReturn('');

        $this->configuration
            ->shouldReceive('iconsPath')
            ->andReturn($this->fs->url() . '/icons');

        $result = $this->renderer->render($iconName);

        expect($result)->toBe('');
    });
});

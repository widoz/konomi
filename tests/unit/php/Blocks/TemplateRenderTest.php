<?php

declare(strict_types=1);

namespace Widoz\Wp\Konomi\Tests\Unit\Blocks;

use Brain\Monkey\Functions;
use Brain\Monkey\Filters;
use Widoz\Wp\Konomi\Blocks\TemplateRender;

describe('Template Render', function (): void {
    it('render a template with given arguments', function (): void {
        $data = ['name' => 'Konomi'];
        $path = 'hello-konomi';
        $templateRenderer = TemplateRender::new(fixturesDirectory() . '/templates', true);
        $output = $templateRenderer->render($path, $data);
        $cleanedOutput = preg_replace('/([\t\n\r]+|\s{2,})/', '', $output);
        expect($cleanedOutput)->toBe('<div class="fixture"><p>Hello Konomi</p></div>');
    });

    it('Filter data and path before rendering', function (): void {
        $data = ['name' => 'Konomi'];
        $path = 'hello-konomi';
        $fullPath = fixturesDirectory() . '/templates/hello-konomi.php';
        $templateRenderer = TemplateRender::new(fixturesDirectory() . '/templates', true);
        Filters\expectApplied('konomi.template.render.data')->once()->with($data, $fullPath);
        Filters\expectApplied('konomi.template.render.path')->once()->with($fullPath, $data);
        $templateRenderer->render($path, $data);
    });

    it('Do not append php extension if already provided', function (): void {
        $data = ['name' => 'Konomi'];
        $path = 'hello-konomi.php';
        $fullPath = fixturesDirectory() . '/templates/hello-konomi.php';
        $templateRenderer = TemplateRender::new(fixturesDirectory() . '/templates', true);
        Functions\expect('realpath')->with($fullPath)->andReturnFirstArg();
        $templateRenderer->render($path, $data);
    });

    it('Throw Exception if the template file does not exists', function (): void {
        $data = ['name' => 'Konomi'];
        $path = 'does-not-exists';
        $templateRenderer = TemplateRender::new(fixturesDirectory() . '/templates', true);
        $templateRenderer->render($path, $data);
    })->expectException(\RuntimeException::class);

    it('Re-throw exception if Debugging mode is active when rendering', function (): void {
        $path = 'throw-exception';
        TemplateRender::new(fixturesDirectory() . '/templates', true)->render($path, []);
    })->expectException(\Exception::class);

    it(
        'Render empty string when throwing during rendering but debugging mode is inactive',
        function (): void {
            $path = 'throw-exception';
            $templateRenderer = TemplateRender::new(
                fixturesDirectory() . '/templates',
                false
            );
            $output = $templateRenderer->render($path, []);
            expect($output)->toBe('');
        }
    );
});

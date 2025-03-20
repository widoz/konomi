<?php
// This file is generated. Do not modify it manually.
return array(
	'Like' => array(
		'$schema' => 'https://schemas.wp.org/trunk/block.json',
		'apiVersion' => 3,
		'name' => 'konomi/like',
		'version' => '1.0.0',
		'title' => 'Like',
		'category' => 'design',
		'icon' => 'smiley',
		'description' => 'Like',
		'example' => array(
			
		),
		'attributes' => array(
			'inactiveColor' => array(
				'type' => 'string'
			),
			'activeColor' => array(
				'type' => 'string'
			)
		),
		'supports' => array(
			'html' => false,
			'interactivity' => true
		),
		'textdomain' => 'konomi',
		'editorScript' => 'file:./dist/konomi-like-block.js',
		'viewScriptModule' => 'file:./build-module/konomi-like-block-view.js',
		'style' => 'file:./dist/style-konomi-like-block.css',
		'render' => 'file:./render.php',
		'blockHooks' => array(
			'core/post-title' => 'after'
		)
	)
);

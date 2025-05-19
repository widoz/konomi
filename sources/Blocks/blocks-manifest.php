<?php
// This file is generated. Do not modify it manually.
return array(
	'Bookmark' => array(
		'$schema' => 'https://schemas.wp.org/trunk/block.json',
		'apiVersion' => 3,
		'name' => 'konomi/bookmark',
		'version' => '1.0.0',
		'title' => 'Bookmark',
		'category' => 'design',
		'icon' => 'smiley',
		'description' => 'Bookmark',
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
		'editorScript' => 'file:./dist/js/konomi-bookmark-block.js',
		'viewScriptModule' => 'file:./build-module/konomi-bookmark-block-view.js',
		'style' => 'file:./dist/css/style-konomi-bookmark-block.css',
		'render' => 'file:./render.php'
	),
	'Konomi' => array(
		'$schema' => 'https://schemas.wp.org/trunk/block.json',
		'apiVersion' => 3,
		'name' => 'konomi/konomi',
		'version' => '1.0.0',
		'title' => 'Konomi',
		'category' => 'design',
		'icon' => 'smiley',
		'description' => 'Konomi',
		'example' => array(
			
		),
		'attributes' => array(
			'active' => array(
				'type' => 'array',
				'default' => array(
					'like',
					'bookmark'
				)
			)
		),
		'supports' => array(
			'html' => false,
			'interactivity' => true
		),
		'allowedBlocks' => array(
			'core/group'
		),
		'textdomain' => 'konomi',
		'editorScript' => 'file:./dist/js/konomi-konomi-block.js',
		'viewScriptModule' => 'file:./build-module/konomi-konomi-block-view.js',
		'viewStyle' => 'file:./dist/css/style-konomi-konomi-block.css',
		'render' => 'file:./render.php',
		'blockHooks' => array(
			'core/post-title' => 'after'
		)
	),
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
		'editorScript' => 'file:./dist/js/konomi-like-block.js',
		'viewScriptModule' => 'file:./build-module/konomi-like-block-view.js',
		'style' => 'file:./dist/css/style-konomi-like-block.css',
		'render' => 'file:./render.php'
	)
);

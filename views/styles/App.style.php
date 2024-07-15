<?php

use PhpSlides\Frontend\Style;
use PhpSlides\Frontend\StyleSheet;
use PhpSlides\Frontend\Module\export;

$style = StyleSheet::create([
	'AppStyle' => [
		Style::Width => 100,
		Style::Height => 200,
		Style::BorderRadius => 5,
		Style::Color => Color::Blue,
		Style::BackgroundImage => asset('bg.png')
	],
	'Text' => [
		Style::Color => Color::White,
		Style::FontSize => Font::Base,
		Style::FontWeight => Font::Bold
	]
]);

export($style, 'AppStyle');

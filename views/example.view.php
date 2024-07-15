<?php

// use PhpSlides\Frontend\DOM;
// use PhpSlides\Frontend\Element;
// use PhpSlides\Frontend\Module\export;
// use PhpSlides\Frontend\Module\import;

interface Element
{
	public static function div(...$args);
	public static function p(...$args);
	public static function h1(...$args);
	public static function h2(...$args);
	public static function h3(...$args);
	public static function br(...$args);
	public static function input(...$args);
	public static function label(...$args);
	public static function span(...$args);
	public static function form(...$args);
}

interface DOM
{
	public static function component($arg);
	public static function create($arg);
	public static function import($arg);
	public static function export($arg);
}

DOM::component('app')->add(
	[],
	Element::div([], Element::h1([], 'H1 Text')),
	Element::div(
		[],
		Element::h3([], 'H3 Text'),
		Element::span([], 'Span Text'),
		Element::span([], 'Second Span Text'),
		Element::p([], 'Paragraph Text')
	)
);

DOM::create('form')->root(
	['id' => 'root'],
	Element::form(['method' => 'post']),
	Element::h1([], 'Select Provider'),
	Element::div(
		[],
		Element::input(['type' => 'radio', 'name' => 'provider', 'value' => 'Google', 'id' => 'providerGoogle']),
		Element::label(['for' => 'providerGoogle'], 'Google'),
		Element::br(),

		Element::input(['type' => 'radio', 'name' => 'provider', 'value' => 'Yahoo', 'id' => 'providerYahoo']),
		Element::label(['for' => 'providerYahoo'], 'Yahoo'),
		Element::br(),

		Element::input(['type' => 'radio', 'name' => 'provider', 'value' => 'Microsoft', 'id' => 'providerMicrosoft']),
		Element::label(['for' => 'providerMicrosoft'], 'Microsoft'),
		Element::br(),

		Element::input(['type' => 'radio', 'name' => 'provider', 'value' => 'Azure', 'id' => 'providerAzure']),
		Element::label(['for' => 'providerAzure'], 'Azure'),
		Element::br(),

		Element::h1([], 'Enter id and secret'),
		Element::p([], 'These details are obtained by setting up an app in your provider\'s developer console.'),

		Element::p([], 'ClientId:'),
		Element::input(['type' => 'text', 'name' => 'clientId']),

		Element::p([], 'ClientSecret:'),
		Element::input(['type' => 'text', 'name' => 'clientSecret']),

		Element::p([], 'TenantID (only relevant for Azure):'),
		Element::input(['type' => 'text', 'name' => 'tenantId']),
		Element::input(['type' => 'submit', 'value' => 'Continue']),
	),
);

//? DOM::export('app');
DOM::export(['app', 'form']);

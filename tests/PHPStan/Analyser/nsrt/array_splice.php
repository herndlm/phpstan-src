<?php

namespace ArraySplice;

use function PHPStan\Testing\assertType;

final class Foo
{
	/** @var bool */
	public $abc = false;

	/** @var string */
	public $def = 'def';
}

/**
 * @param array<int, int> $arr
 * @return void
 */
function insertViaArraySplice(array $arr): void
{
	$brr = $arr;
	array_splice($brr, 0, 0, 1);
	assertType('non-empty-array<int, int>', $brr);

	$brr = $arr;
	array_splice($brr, 0, 0, [1]);
	assertType('non-empty-array<int, int>', $brr);

	$brr = $arr;
	array_splice($brr, 0, 0, '');
	assertType('non-empty-array<int, \'\'|int>', $brr);

	$brr = $arr;
	array_splice($brr, 0, 0, ['']);
	assertType('non-empty-array<int, \'\'|int>', $brr);

	$brr = $arr;
	array_splice($brr, 0, 0, null);
	assertType('array<int, int>', $brr);

	$brr = $arr;
	array_splice($brr, 0, 0, [null]);
	assertType('non-empty-array<int, int|null>', $brr);

	$brr = $arr;
	array_splice($brr, 0, 0, new Foo());
	assertType('non-empty-array<int, bool|int|string>', $brr);

	$brr = $arr;
	array_splice($brr, 0, 0, [new \stdClass()]);
	assertType('non-empty-array<int, int|stdClass>', $brr);

	$brr = $arr;
	array_splice($brr, 0, 0, false);
	assertType('non-empty-array<int, int|false>', $brr);

	$brr = $arr;
	array_splice($brr, 0, 0, [false]);
	assertType('non-empty-array<int, int|false>', $brr);

	$brr = $arr;
	array_splice($brr, 0);
	assertType('array{}', $brr);
}

function constantArrays(array $arr, array $arr2): void
{
	/** @var array{17: 'foo', b: 'bar', 19: 'baz'} $arr */
	array_splice($arr, 0, 1, ['hello']);
	assertType('array{0: \'hello\', b: \'bar\', 1: \'baz\'}', $arr);

	/** @var array{17: 'foo', b: 'bar', 19: 'baz'} $arr */
	array_splice($arr, 1, 2, ['hello']);
	assertType('array{\'foo\', \'hello\'}', $arr);

	/** @var array{17: 'foo', b: 'bar', 19: 'baz'} $arr */
	array_splice($arr, 0, -1, ['hello']);
	assertType('array{\'hello\', \'baz\'}', $arr);

	/** @var array{17: 'foo', b: 'bar', 19: 'baz'} $arr */
	array_splice($arr, 0, -2, ['hello']);
	assertType('array{0: \'hello\', b: \'bar\', 1: \'baz\'}', $arr);

	/** @var array{17: 'foo', b: 'bar', 19: 'baz'} $arr */
	array_splice($arr, -1, -1, ['hello']);
	assertType('array{0: \'foo\', b: \'bar\', 1: \'hello\', 2: \'baz\'}', $arr);

	/** @var array{17: 'foo', b: 'bar', 19: 'baz'} $arr */
	array_splice($arr, -2, -2, ['hello']);
	assertType('array{0: \'foo\', 1: \'hello\', b: \'bar\', 2: \'baz\'}', $arr);

	/** @var array{17: 'foo', b: 'bar', 19: 'baz'} $arr */
	array_splice($arr, 99, 0, ['hello']);
	assertType('array{0: \'foo\', b: \'bar\', 1: \'baz\'}', $arr);

	/** @var array{17: 'foo', b: 'bar', 19: 'baz'} $arr */
	array_splice($arr, 1, 99, ['hello']);
	assertType('array{\'foo\', \'hello\'}', $arr);

	/** @var array{17: 'foo', b: 'bar', 19: 'baz'} $arr */
	array_splice($arr, -99, 99, ['hello']);
	assertType('array{\'hello\'}', $arr);

	/** @var array{17: 'foo', b: 'bar', 19: 'baz'} $arr */
	array_splice($arr, 0, -99, ['hello']);
	assertType('array{0: \'hello\', 1: \'foo\', b: \'bar\', 2: \'baz\'}', $arr);

	/** @var array{17: 'foo', b: 'bar', 19: 'baz'} $arr */
	array_splice($arr, -2, 1, ['hello']);
	assertType('array{\'foo\', \'hello\', \'baz\'}', $arr);

	/** @var array{17: 'foo', b: 'bar', 19: 'baz'} $arr */
	array_splice($arr, -1, 1, ['hello']);
	assertType('array{0: \'foo\', b: \'bar\', 1: \'hello\'}', $arr);

	/** @var array{17: 'foo', b: 'bar', 19: 'baz'} $arr */
	array_splice($arr, 0, null, ['hello']);
	assertType('array{\'hello\'}', $arr);

	/** @var array{17: 'foo', b: 'bar', 19: 'baz'} $arr */
	array_splice($arr, 0);
	assertType('array{}', $arr);

	/** @var array{17: 'foo', b: 'bar', 19: 'baz'} $arr */
	/** @var array<\stdClass> $arr2 */
	array_splice($arr, 1, 1, $arr2);
	assertType('non-empty-array<int<0, max>, \'baz\'|\'foo\'|stdClass>', $arr);

	/** @var array{17: 'foo', b: 'bar', 19: 'baz'} $arr */
	/** @var array<\stdClass> $arr2 */
	array_splice($arr, 0, 1, $arr2);
	assertType('non-empty-array<\'b\'|int<0, max>, \'bar\'|\'baz\'|stdClass>', $arr);
}

function constantArraysWithOptionalKeys(array $arr): void
{
	/**
	 * @see https://3v4l.org/jrqoZ
	 * @var array{a?: 0, b: 1, c: 2} $arr
	 */
	array_splice($arr, 0, 1, ['hello']);
	assertType('array{0: \'hello\', b?: 1, c: 2}', $arr);

	/**
	 * @see https://3v4l.org/lbUJG
	 * @var array{a?: 0, b: 1, c: 2} $arr
	 */
	array_splice($arr, 1, 1, ['hello']);
	assertType('array{a: 0, 0: \'hello\', c: 2}|array{b: 1, 0: \'hello\'}', $arr);

	/**
	 * @see https://3v4l.org/7uPmV
	 * @var array{a?: 0, b: 1, c: 2} $arr
	 */
	array_splice($arr, -1, 0, ['hello']);
	assertType('array{a?: 0, b: 1, 0: \'hello\', c: 2}', $arr);

	/**
	 * @see https://3v4l.org/hB8pG
	 * @var array{a?: 0, b: 1, c: 2} $arr
	 */
	array_splice($arr, 0, -1, ['hello']);
	assertType('array{0: \'hello\', c: 2}', $arr);

	/**
	 * @see https://3v4l.org/TjfHT
	 * @var array{a: 0, b?: 1, c: 2} $arr
	 */
	array_splice($arr, 0, 1, ['hello']);
	assertType('array{0: \'hello\', b?: 1, c: 2}', $arr);

	/**
	 * @see https://3v4l.org/D8PSE
	 * @var array{a: 0, b?: 1, c: 2} $arr
	 */
	array_splice($arr, 1, 1, ['hello']);
	assertType('array{a: 0, 0: \'hello\', c?: 2}', $arr);

	/**
	 * @see https://3v4l.org/8RfDs
	 * @var array{a: 0, b?: 1, c: 2} $arr
	 */
	array_splice($arr, -1, 0, ['hello']);
	assertType('array{a: 0, b?: 1, 0: \'hello\', c: 2}', $arr);

	/**
	 * @see https://3v4l.org/sPfpN
	 * @var array{a: 0, b?: 1, c: 2} $arr
	 */
	array_splice($arr, 0, -1, ['hello']);
	assertType('array{0: \'hello\', c: 2}', $arr);

	/**
	 * @see https://3v4l.org/Ddpku
	 * @var array{a: 0, b: 1, c?: 2} $arr
	 */
	array_splice($arr, 0, 1, ['hello']);
	assertType('array{0: \'hello\', b: 1, c?: 2}', $arr);

	/**
	 * @see https://3v4l.org/O4LLi
	 * @var array{a: 0, b: 1, c?: 2} $arr
	 */
	array_splice($arr, 1, 1, ['hello']);
	assertType('array{a: 0, 0: \'hello\', c?: 2}', $arr);

	/**
	 * @see https://3v4l.org/QQO84
	 * @var array{a: 0, b: 1, c?: 2} $arr
	 */
	array_splice($arr, -1, 0, ['hello']);
	assertType('array{a: 0, b: 1, 0: \'hello\', c?: 2}', $arr);

	/**
	 * @see https://3v4l.org/K5RDp
	 * @var array{a: 0, b: 1, c?: 2} $arr
	 */
	array_splice($arr, 0, -1, ['hello']);
	assertType('array{0: \'hello\', b: 1}|array{0: \'hello\', c: 2}', $arr);

	/**
	 * @see https://3v4l.org/luTES
	 * @var array{a: 0, b?: 1, c?: 2, d: 3} $arr
	 */
	array_splice($arr, 1, 2, ['hello']);
	assertType('array{a: 0, 0: \'hello\', d?: 3}', $arr);

	/**
	 * @see https://3v4l.org/DvRmU
	 * @var array{a: 0, b?: 1, c?: 2, d: 3} $arr
	 */
	array_splice($arr, -2, 2, ['hello']);
	assertType('array{a?: 0, b?: 1, 0: \'hello\'}', $arr);
}

function offsets(array $arr): void
{
	if (array_key_exists(1, $arr)) {
		array_splice($arr, 0, 1, 'hello');
		assertType('non-empty-array', $arr);
	}

	if (array_key_exists(1, $arr)) {
		array_splice($arr, 0, 0, 'hello');
		assertType('non-empty-array&hasOffset(1)', $arr);
	}

	if (array_key_exists(1, $arr) && $arr[1] === 'foo') {
		array_splice($arr, 0, 1, 'hello');
		assertType('non-empty-array', $arr);
	}

	if (array_key_exists(1, $arr) && $arr[1] === 'foo') {
		array_splice($arr, 0, 0, 'hello');
		assertType('non-empty-array&hasOffsetValue(1, \'foo\')', $arr);
	}
}

function lists(array $arr): void
{
	/** @var list<string> $arr */
	array_splice($arr, 0, 1, 'hello');
	assertType('non-empty-list<string>', $arr);

	/** @var list<string> $arr */
	array_splice($arr, 0, 0, 'hello');
	assertType('non-empty-list<string>', $arr);

	/** @var list<string> $arr */
	array_splice($arr, 0, null, 'hello');
	assertType('non-empty-list<string>', $arr);

	/** @var list<string> $arr */
	array_splice($arr, 0, null);
	assertType('array{}', $arr);

	/** @var list<string> $arr */
	array_splice($arr, 0, 1);
	assertType('list<string>', $arr);
}

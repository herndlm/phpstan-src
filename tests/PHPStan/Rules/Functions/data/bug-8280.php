<?php declare(strict_types = 1);

namespace Bug8280;

use function PHPStan\Testing\assertType;

/**
 * @param list<string> $var
 */
function foo($var): void {}

/** @var string|list<string>|null $var */
if (null !== $var) {
	assertType('list<string>', (array) $var);
	foo((array) $var); // should work the same as line below
	assertType('list<string>', !is_array($var) ? [$var] : $var);
	foo(!is_array($var) ? [$var] : $var);
}

/**
 * @param non-empty-array<string> $var
 */
function bar($var): void {}

/** @var string|non-empty-array<string>|null $var */
if (null !== $var) {
	assertType('non-empty-array<string>', (array) $var);
	bar((array) $var); // should work the same as line below
	assertType('non-empty-array<string>', !is_array($var) ? [$var] : $var);
	bar(!is_array($var) ? [$var] : $var);
}

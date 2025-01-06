<?php
declare(strict_types=1);

namespace Snippet;

use Iterator;
use IteratorAggregate;
use function array_key_exists;
use function count;
use function PHPStan\Testing\assertType;

/**
 * @template T
 */
interface TypeGenerator
{
	/**
	 * @return T
	 */
	public function __invoke();
}

/**
 * @template TKey of array-key
 * @template T
 *
 * @implements TypeGenerator<array<TKey, T>>
 */
final class ArrayType implements TypeGenerator
{
	/**
	 * @var list<TypeGenerator<TKey>>
	 */
	private array $keys = [];

	/**
	 * @var list<TypeGenerator<T>>
	 */
	private array $values = [];

	/**
	 * @param TypeGenerator<TKey> $key
	 * @param TypeGenerator<T> $value
	 */
	public function __construct(TypeGenerator $key, TypeGenerator $value)
	{
		$this->keys[] = $key;
		$this->values[] = $value;
	}

	/**
	 * @return array<TKey, T>
	 */
	public function __invoke(): array
	{
		$keys = $values = [];
		$countKeys = count($this->keys);

		for ($i = 0; count($keys) < $countKeys; ++$i) {
			$key = ($this->keys[$i])();

			if (array_key_exists($key, $keys)) {
				--$i;

				continue;
			}

			$keys[$key] = $key;
		}

		foreach ($this->values as $value) {
			$values[] = ($value)();
		}

		assertType('array<TKey of (int|string) (class Snippet\ArrayType, argument), TKey of (int|string) (class Snippet\ArrayType, argument)>', $keys);

		return array_combine($keys, $values);
	}

}

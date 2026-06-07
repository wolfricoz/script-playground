<?php

namespace sorting;

use PHPUnit\Framework\Attributes\CoversFunction;
use PHPUnit\Framework\TestCase;
require_once 'Sorting/QuickSort.php';

// Safely require or autoload your function file here if needed:
// require_once __DIR__ . '/QuickSort.php';

class QuickSortTest extends TestCase
{
    /**
     * Helper method to assert execution time.
     * Fails the test if the callback takes longer than the specified seconds.
     */
    private function assertExecutionTimeLessThan(float $seconds, callable $callback)
    {
        $startTime = microtime(true);
        $callback();
        $endTime = microtime(true);
        $elapsedTime = $endTime - $startTime;

        $this->assertLessThan($seconds, $elapsedTime, "Execution took too long: {$elapsedTime}s (Limit: {$seconds}s)");
    }

    // 1. THE CLASSIC TRAUMATIC CASE: Already Sorted Array
    // Challenge: Naive pivot choices (like picking the first/last element) will degrade to O(n^2) and cause a stack overflow.
    public function testQuickSortAlreadySortedArray()
    {
        $input = range(1, 5000);
        $expected = $input;

        $this->assertExecutionTimeLessThan(0.1, function() use ($input, $expected) {
            $this->assertEquals($expected, quickSort($input));
        });
    }

    // 2. THE REVERSE CRITICAL CASE: Strictly Decreasing Array
    // Challenge: Tests if the partitioning strategy handles perfectly inverted data efficiently.
    public function testQuickSortReverseSortedArray()
    {
        $input = range(5000, 1);
        $expected = range(1, 5000);

        $this->assertExecutionTimeLessThan(0.1, function() use ($input, $expected) {
            $this->assertEquals($expected, quickSort($input));
        });
    }

    // 3. THE WALL OF DUPLICATES: Array with All Identical Elements
    // Challenge: Poorly implemented Hoare or Lomuto partitioning can result in O(n^2) time if duplicates aren't properly distributed.
    public function testQuickSortAllIdenticalElements()
    {
        $input = array_fill(0, 5000, 42);
        $expected = $input;

        $this->assertExecutionTimeLessThan(0.1, function() use ($input, $expected) {
            $this->assertEquals($expected, quickSort($input));
        });
    }

    // 4. THE STRESSED HIGH-VOLUME: Large Random Dataset
    // Challenge: General performance check. Must handle 50,000 items rapidly without memory exhaustion.
    public function testQuickSortLargeRandomDataset()
    {
        $input = [];
        for ($i = 0; $i < 50000; $i++) {
            $input[] = rand(-100000, 100000);
        }
        $expected = $input;
        sort($expected);

        $this->assertExecutionTimeLessThan(0.5, function() use ($input, $expected) {
            $this->assertEquals($expected, quickSort($input));
        });
    }

    // 5. THE TWO-VALUE OCEAN: Large Array with Only Two Unique Values
    // Challenge: Frequently causes poorly optimized quicksorts to hang or loop endlessly on pivot swaps.
    public function testQuickSortOnlyTwoUniqueValues()
    {
        $input = [];
        for ($i = 0; $i < 10000; $i++) {
            $input[] = ($i % 2 === 0) ? 7 : 99;
        }
        $expected = $input;
        sort($expected);

        $this->assertExecutionTimeLessThan(0.1, function() use ($input, $expected) {
            $this->assertEquals($expected, quickSort($input));
        });
    }

    // 6. THE MINIMALIST: Empty Array and Single Element
    // Challenge: Must safely return without throwing errors, entering infinite loops, or breaking type hints.
    public function testQuickSortEmptyAndSingleElement()
    {
        $this->assertEquals([], quickSort([]));
        $this->assertEquals([42], quickSort([42]));
    }

    // 7. THE NEGATIVE REALM: Array with Negative and Duplicate Numbers
    // Challenge: Ensures signs do not break comparison logic or pivot placement.
    public function testQuickSortNegativeAndDuplicateNumbers()
    {
        $input = [-5, 10, -5, 0, -100, 50, 0, -5, 25];
        $expected = [-100, -5, -5, -5, 0, 0, 10, 25, 50];

        $this->assertEquals($expected, quickSort($input));
    }

    // 8. THE SHAKEN RECORD: Alternating High and Low Values (Sawtooth)
    // Challenge: Tests the algorithm's resilience against structured, non-random data variations that trick median-of-three calculations.
    public function testQuickSortSawtoothPattern()
    {
        $input = [];
        for ($i = 0; $i < 5000; $i++) {
            $input[] = ($i % 2 === 0) ? (10000 - $i) : $i;
        }
        $expected = $input;
        sort($expected);

        $this->assertExecutionTimeLessThan(0.1, function() use ($input, $expected) {
            $this->assertEquals($expected, quickSort($input));
        });
    }

    // 9. THE FLOAT ZONE: Sorting Floating Point Numbers
    // Challenge: Verifies precision handling; strict integer comparisons (`===` vs `==`) or type-casting issues can break the sort.
    public function testQuickSortFloatingPointNumbers()
    {
        $input = [3.14, 2.71, -0.5, 0.001, 2.71, -2.5];
        $expected = [-2.5, -0.5, 0.001, 2.71, 2.71, 3.14];

        $this->assertEquals($expected, quickSort($input));
    }

    // 10. THE PIPE CLEANER: Pre-sorted Blocks (Pipe Organ Profile)
    // Challenge: An array that goes up then down (e.g., 1,2,3,4,3,2,1). This is a known worst-case killer for many standard pivot algorithms.
    public function testQuickSortPipeOrganProfile()
    {
        $ascending = range(1, 2500);
        $descending = range(2500, 1);
        $input = array_merge($ascending, $descending);

        $expected = $input;
        sort($expected);

        $this->assertExecutionTimeLessThan(0.1, function() use ($input, $expected) {
            $this->assertEquals($expected, quickSort($input));
        });
    }
}
<?php declare(strict_types=1);

namespace AdventOfCode2020;

echo sprintf('Puzzle 9 part 1: %d'.PHP_EOL, (new Puzzle09())->part1());
echo sprintf('Puzzle 9 part 2: %d'.PHP_EOL, (new Puzzle09())->part2());

final class Puzzle09
{
    public function part1(bool $test = false): int
    {
        $preamble = $test ? 5 : 25;

        $numbers = $this->getNumbers($test);
        $numbersCount = count($numbers);
        $y = 0;

        for ($i = $preamble; $i <= $numbersCount; $i++)
        {
            $number = $numbers[$i];

            $numbersToCheck = array_slice($numbers, $y++, $preamble);

            if (!$this->check($number, $numbersToCheck)) {
                return $number;
            }
        }

        return 0;
    }

    public function part2(bool $test = false): int
    {
        $target = $this->part1($test);

        $numbers = $this->getNumbers($test);

        foreach ($numbers as $i => $number) {
            $y = $i;
            $sum = 0;

            while ($sum < $target && $y < count($numbers)) {
                $sum += $numbers[$y];

                if ($sum === $target) {
                    $slice = array_slice($numbers, $i, ($y-$i+1));
                    return min($slice) + max($slice);
                }

                $y++;
            }
        }

        return 0;
    }

    private function check(int $number, array $numbers): bool
    {
        foreach ($numbers as $numX) {
            foreach ($numbers as $numY) {
                if ($numX !== $numY && ($number === ($numX+$numY))) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * @return int[]
     */
    private function getNumbers(bool $test = false): array
    {
        $filename = $test
            ? 'Input09-Test.txt'
            : 'Input09.txt';

        $fp = @fopen($filename, 'rb');

        $numbers = [];

        if ($fp) {
            while (($line = fgets($fp, 4096)) !== false) {
                $numbers[] = (int)$line;
            }
        }

        return $numbers;
    }
}

<?php declare(strict_types=1);

namespace AdventOfCode2020;

echo sprintf('Puzzle 6 part 1: %d'.PHP_EOL, (new Puzzle06())->part1());
echo sprintf('Puzzle 6 part 2: %d'.PHP_EOL, (new Puzzle06())->part2());

final class Puzzle06
{
    public function part1(): int
    {
        $totalCount = 0;

        foreach ($this->getGroups() as $group) {
            $groupString = implode('', $group);
            $totalCount += strlen(count_chars($groupString, 3));
        }

        return $totalCount;
    }

    public function part2(): int
    {
        $totalCount = 0;

        foreach ($this->getGroups() as $group) {
            $groupCount = count($group);
            $groupString = implode('', $group);
            $questionsAnswered = count_chars($groupString, 1);

            foreach ($questionsAnswered as $questionAnswered) {
                if ($questionAnswered === $groupCount) {
                    $totalCount++;
                }
            }
        }

        return $totalCount;
    }

    private function getGroups(): array
    {
        $fp = @fopen('Input06.txt', 'rb');

        $groups = [];
        $i = 0;

        if ($fp) {
            while (($line = fgets($fp, 4096)) !== false) {
                if ("\n" !== $line) {
                    $groups[$i][] = trim(preg_replace('/\s+/', ' ', $line));
                } else {
                    $i++;
                }
            }
        }

        return $groups;
    }
}

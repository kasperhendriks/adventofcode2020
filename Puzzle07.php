<?php declare(strict_types=1);

namespace AdventOfCode2020;

echo sprintf('Puzzle 7 part 1: %d'.PHP_EOL, (new Puzzle07())->part1());
echo sprintf('Puzzle 7 part 2: %d'.PHP_EOL, (new Puzzle07())->part2());

final class Puzzle07
{
    private const MY_BAG = 'shiny gold';

    public function part1(): int
    {
        $totalFound = 0;

        $rules = $this->getRules();

        foreach ($rules as $ruleName => $bags) {
            if ($this->checkRuleForBag($rules, $bags, self::MY_BAG)) {
                $totalFound++;
            }
        }

        return $totalFound;
    }

    public function part2(): int
    {
        $rules = $this->getRules();

        return $this->getBagCount($rules, self::MY_BAG);
    }

    private function checkRuleForBag(array $rules, array $bags, string $myBag)
    {
        if (array_key_exists($myBag, $bags)) {
            return true;
        }

        foreach ($bags as $bag => $number) {
            if ($this->checkRuleForBag($rules,$rules[$bag] ?? [], $myBag)) {
                return true;
            }
        }

        return false;
    }

    private function getBagCount(array $rules, string $bagName): int
    {
        $total = 0;

        $items = $rules[$bagName];

        foreach ($items as $bag => $number) {
            $total += $number;
            $total += $this->getBagCount($rules, $bag) * $number;
        }

        return $total;
    }

    private function getRules(): array
    {
        $fp = @fopen('Input07.txt', 'rb');

        $rules = [];

        if ($fp) {
            while (($line = fgets($fp, 4096)) !== false) {
                $rules[] = trim(preg_replace('/\s+/', ' ', $line));
            }
        }

        return $this->parseRules($rules);
    }

    private function parseRules(array $rules): array
    {
        $parsedRules = [];

        foreach ($rules as $rule) {
            $data = explode('contain', $rule);

            $bagName = $this->cleanUpBagName($data[0]);
            $ruleBags = [];

            foreach (explode(',', $data[1]) as $ruleBag) {
                $number = $this->getBagNumber($ruleBag);
                if ($number > 0) {
                    $ruleBags[$this->cleanUpBagName($ruleBag)] = $number;
                }
            }

            $parsedRules[$bagName] = $ruleBags;
        }

        return $parsedRules;
    }

    private function getBagNumber(string $rule): int
    {
        return (int) preg_replace('/[^0-9]/', '', $rule);
    }
    private function cleanUpBagName(string $bagName): string
    {
        return trim(preg_replace('/[0-9]+/', '', str_replace('.', '', str_replace('bag', '', str_replace('bags', '', $bagName)))));
    }
}

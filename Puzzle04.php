<?php declare(strict_types=1);

namespace AdventOfCode2020;

echo sprintf('Puzzle 4 part 1: %d'.PHP_EOL, (new Puzzle04())->part1());
echo sprintf('Puzzle 4 part 2: %d'.PHP_EOL, (new Puzzle04())->part2());

final class Puzzle04
{
    public function part1(): int
    {
        $fp = @fopen('Input04.txt', 'rb');

        $validPassports = 0;
        $passports = [];
        $i = 0;

        if ($fp) {
            while (($line = fgets($fp, 4096)) !== false) {
                if ("\n" !== $line) {
                    $passports[$i][] = trim(preg_replace('/\s+/', ' ', $line));
                } else {
                    $i++;
                }
            }
        }

        foreach ($passports as $passport) {
            $passportArray = explode(' ', implode(' ', $passport));
            $parsedPassportArray = [];

            foreach ($passportArray as $passportItem) {
                [$key, $value] = explode(':', $passportItem);
                $parsedPassportArray[$key] = $value;
            }

            $valid = true;

            foreach ($this->getMandatoryFields() as $field) {
                $valid = $valid && array_key_exists($field, $parsedPassportArray);
            }

            if ($valid) {
                $validPassports++;
            }
        }

        return $validPassports;
    }

    public function part2(): int
    {
        $fp = @fopen('Input04.txt', 'rb');

        $validPassports = 0;
        $passports = [];
        $i = 0;

        if ($fp) {
            while (($line = fgets($fp, 4096)) !== false) {
                if ("\n" !== $line) {
                    $passports[$i][] = trim(preg_replace('/\s+/', ' ', $line));
                } else {
                    $i++;
                }
            }
        }

        foreach ($passports as $passport) {
            $passportArray = explode(' ', implode(' ', $passport));
            $parsedPassportArray = [];

            foreach ($passportArray as $passportItem) {
                [$key, $value] = explode(':', $passportItem);
                $parsedPassportArray[$key] = $value;
            }

            $mandatoryFieldsExist = true;

            foreach ($this->getMandatoryFields() as $field) {
                $mandatoryFieldsExist = $mandatoryFieldsExist && array_key_exists($field, $parsedPassportArray);
            }

            if ($mandatoryFieldsExist) {
                $allValuesOk =
                    true
                    && $this->validateBirthYear((int)$parsedPassportArray['byr'])
                    && $this->validateIssueYear((int)$parsedPassportArray['iyr'])
                    && $this->validateExpirationYear((int)$parsedPassportArray['eyr'])
                    && $this->validateHeight($parsedPassportArray['hgt'])
                    && $this->validateHairColour($parsedPassportArray['hcl'])
                    && $this->validateEyeColour($parsedPassportArray['ecl'])
                    && $this->validatePassportId($parsedPassportArray['pid'])
                ;

                if ($allValuesOk) {
                    $validPassports++;
                }
            }
        }

        return $validPassports;
    }

    private function validateBirthYear(int $year): bool
    {
        return (1920 <= $year) && ($year <= 2002);
    }

    private function validateIssueYear(int $year): bool
    {
        return (2010 <= $year) && ($year <= 2020);
    }

    private function validateExpirationYear(int $year): bool
    {
        return (2020 <= $year) && ($year <= 2030);
    }

    private function validateHeight(string $height): bool
    {
        $value = (int) filter_var($height, FILTER_SANITIZE_NUMBER_INT);

        preg_match('/\d+cm/', $height, $cm);
        preg_match('/\d+in/', $height, $in);

        if (!empty($cm)) {
            return (150 <= $value) && ($value <= 193);
        }


        if (!empty($in)) {
            return (59 <= $value) && ($value <= 76);
        }

        return false;
    }

    private function validateHairColour(string $colour): bool
    {
        return (7 === strlen($colour)) && (preg_match('/#[a-z0-9]{6}/', $colour));
    }

    private function validateEyeColour(string $colour): bool
    {
        return in_array($colour, ['amb', 'blu', 'brn', 'gry', 'grn', 'hzl', 'oth',]);
    }

    private function validatePassportId(string $id): bool
    {
        return (9 === strlen($id)) && (ctype_digit($id));
    }

    private function getMandatoryFields(): array
    {
        return [
            'byr', // (Birth Year)
            'iyr', // (Issue Year)
            'eyr', // (Expiration Year)
            'hgt', // (Height)
            'hcl', // (Hair Color)
            'ecl', // (Eye Color)
            'pid', // (Passport ID)
        ];
    }
}

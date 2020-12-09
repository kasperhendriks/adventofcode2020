<?php declare(strict_types=1);

namespace AdventOfCode2020;

echo sprintf('Puzzle 8 part 1: %d'.PHP_EOL, (new Puzzle08())->part1());
echo sprintf('Puzzle 8 part 2: %d'.PHP_EOL, (new Puzzle08())->part2());

final class Puzzle08
{
    /** @var Instruction[] $usedInstructions */
    private array $usedInstructions = [];

    private int $accumulator = 0;

    public function part1(): int
    {
        $i = 0;
        $instructions = $this->getInstructions();

        while (true) {
            $instruction = $instructions[$i] ?? null;

            if (null === $instruction || in_array($i, $this->usedInstructions)) {
                return $this->accumulator;
            }

            $this->usedInstructions[] = $i;

            if ($instruction->isAcc()) {
                $i++;
                $this->accumulator += $instruction->getValue();
            } elseif ($instruction->isNop()) {
                $i++;
            } elseif ($instruction->isJmp()) {
                $i += $instruction->getValue();
            }
        }
    }

    public function part2(): int
    {
        $instructions = $this->getInstructions();
        $instructionCount = count($instructions);

        for ($i = 0; $i < $instructionCount; $i++) {
            $tryInstructions = [];

            foreach ($instructions as $k => $in) {
                $tryInstructions[$k] = clone $in;
            }

            if (Operation::JMP === ($tryInstructions[$i])->getOperation()) {
                ($tryInstructions[$i])->setOperation(Operation::NOP);
            } elseif (Operation::NOP === ($tryInstructions[$i])->getOperation()) {
                ($tryInstructions[$i])->setOperation(Operation::JMP);
            } else {
                continue;
            }

            $accumulator = $this->accumulate($tryInstructions);

            if (null !== $accumulator) {
                return $accumulator;
            }
        }

        return 0;
    }

    /**
     * @param Instruction[] $instructions
     */
    private function accumulate(array $instructions): ?int
    {
        $this->usedInstructions = [];
        $i = 0;
        $accumulator = 0;

        while (true) {
            if ($i === count($instructions)) {
                return $accumulator;
            }

            $instruction = $instructions[$i];

            if (in_array($i, $this->usedInstructions)) {
                return null;
            }

            $this->usedInstructions[] = $i;

            if ($instruction->isAcc()) {
                $i++;
                $accumulator += $instruction->getValue();
            } elseif ($instruction->isNop()) {
                $i++;
            } elseif ($instruction->isJmp()) {
                $i += $instruction->getValue();
            }
        }
    }

    /**
     * @return Instruction[]
     */
    private function getInstructions(): array
    {
        $fp = @fopen('Input08.txt', 'rb');

        $instructions = [];

        if ($fp) {
            while (($line = fgets($fp, 4096)) !== false) {
                $instructionArray = explode(' ', trim(preg_replace('/\s+/', ' ', $line)));
                $instructions[] = new Instruction($instructionArray[0], (int)$instructionArray[1]);
            }
        }

        return $instructions;
    }
}

final class Operation
{
    public const NOP = 'nop';
    public const ACC = 'acc';
    public const JMP = 'jmp';
}

final class Instruction
{
    private string $operation;
    private int $value;

    public function __construct(string $operation, int $value)
    {
        $this->operation = $operation;
        $this->value = $value;
    }

    public function getOperation(): string
    {
        return $this->operation;
    }

    public function getValue(): int
    {
        return $this->value;
    }

    public function isNop(): bool
    {
        return Operation::NOP === $this->operation;
    }

    public function isAcc(): bool
    {
        return Operation::ACC === $this->operation;
    }

    public function isJmp(): bool
    {
        return Operation::JMP === $this->operation;
    }

    public function setOperation(string $operation): void
    {
        $this->operation = $operation;
    }
}

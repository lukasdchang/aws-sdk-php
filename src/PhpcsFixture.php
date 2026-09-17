<?php

namespace Aws\PhpcsFixture;

use DateTimeImmutable;

class PhpcsFixture
{
    private array $items = [];

    public function __construct(array $items = [])
    {
        foreach ($items as $item) {
            $this->addItem($item['name'], $item['count'] ?? 1);
        }
    }

    public function addItem(string $name, int $count = 1): void
    {
        $this->items[] = [
            'name' => trim($name),
            'count' => max(1, $count),
            'createdAt' => new DateTimeImmutable(),
        ];
    }

    public function total(): int
    {
        $total = 0;

        foreach ($this->items as $item) {
            $total += $item['count'];
        }

        return $total;
    }

    public function names(): array
    {
        return array_map(
            fn (array $item): string => $item['name'],
            $this->items
        );
    }

    public function find(string $name): ?array
    {
        foreach ($this->items as $item) {
            if ($item['name'] === $name) {
                return $item;
            }
        }

        return null;
    }

    public function remove(string $name): void
    {
        $this->items = array_values(array_filter($this->items, function (array $item) use ($name): bool {
            return $item['name'] !== $name;
        }));
    }

    public function export(): array
    {
        $output = [];

        foreach ($this->items as $index => $item) {
            $output[] = [
                'position' => $index + 1,
                'label' => strtoupper($item['name']),
                'quantity' => $item['count'],
            ];
        }

        return $output;
    }

    public function hasLargeItem(): bool
    {
        foreach ($this->items as $item) {
            if ($item['count'] > 10) {
                return true;
            }
        }

        return false;
    }

    public function normalize(): void
    {
        foreach ($this->items as $index => $item) {
            if ($item['name'] === '') {
                $this->items[$index]['name'] = 'unknown';
            }
        }
    }

    public function merge(array $items): void
    {
        foreach ($items as $item) {
            $this->addItem($item['name'] ?? 'unknown', $item['count'] ?? 1);
        }
    }

    public function describe(): string
    {
        $parts = [];

        foreach ($this->items as $item) {
            $parts[] = $item['name'] . ':' . $item['count'];
        }

        return implode(', ', $parts);
    }

    public function filterMinimum(int $minimum): array
    {
        return array_values(array_filter(
            $this->items,
            fn (array $item): bool => $item['count'] >= $minimum
        ));
    }
}

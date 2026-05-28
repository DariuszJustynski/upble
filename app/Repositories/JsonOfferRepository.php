<?php
declare(strict_types=1);

namespace App\Repositories;

/**
 * Read-only offer repository backed by /data/offers.json.
 * No database required — suitable for shared hosting and rapid iteration.
 * Replace with a PDO-backed repository when a SQL layer is introduced.
 */
class JsonOfferRepository
{
    private array $offers;

    public function __construct()
    {
        $raw          = file_get_contents(DATA . '/offers.json');
        $this->offers = json_decode((string) $raw, true, flags: JSON_THROW_ON_ERROR) ?? [];
    }

    /** Return every offer. */
    public function all(): array
    {
        return $this->offers;
    }

    /** Find a single offer by its id field, or return null. */
    public function find(string $id): ?array
    {
        foreach ($this->offers as $offer) {
            if ((string) $offer['id'] === $id) {
                return $offer;
            }
        }
        return null;
    }

    /** All offers posted by a given employer id. */
    public function byEmployer(string $employerId): array
    {
        return array_values(array_filter(
            $this->offers,
            static fn(array $o): bool => (string) $o['employer_id'] === $employerId
        ));
    }

    /** N most recently created offers, sorted by created_at descending. */
    public function latest(int $limit = 6): array
    {
        $sorted = $this->offers;
        usort($sorted, static fn($a, $b) => strcmp((string) $b['created_at'], (string) $a['created_at']));
        return array_slice($sorted, 0, $limit);
    }

    /** Offers matching a category slug (case-insensitive). */
    public function byCategory(string $category): array
    {
        $needle = strtolower($category);
        return array_values(array_filter(
            $this->offers,
            static fn(array $o): bool => strtolower($o['category']) === $needle
        ));
    }

    /** Full-text search across title, description and location. */
    public function search(string $query): array
    {
        if ($query === '') {
            return $this->offers;
        }
        $q = strtolower($query);
        return array_values(array_filter(
            $this->offers,
            static fn(array $o): bool =>
                str_contains(strtolower($o['title']),       $q) ||
                str_contains(strtolower($o['description']), $q) ||
                str_contains(strtolower($o['location']),    $q)
        ));
    }
}

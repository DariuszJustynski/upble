<?php
declare(strict_types=1);

namespace App\Repositories;

/**
 * Read-only employer repository backed by /data/employers.json.
 */
class JsonEmployerRepository
{
    private array $employers;

    public function __construct()
    {
        $raw             = file_get_contents(DATA . '/employers.json');
        $this->employers = json_decode((string) $raw, true, flags: JSON_THROW_ON_ERROR) ?? [];
    }

    public function all(): array
    {
        return $this->employers;
    }

    public function find(string $id): ?array
    {
        foreach ($this->employers as $employer) {
            if ((string) $employer['id'] === $id) {
                return $employer;
            }
        }
        return null;
    }

    /** Employers in a given city (case-insensitive). */
    public function byCity(string $city): array
    {
        $needle = strtolower($city);
        return array_values(array_filter(
            $this->employers,
            static fn(array $e): bool => strtolower($e['city']) === $needle
        ));
    }

    /** Top N employers sorted by rating descending. */
    public function topRated(int $limit = 5): array
    {
        $sorted = $this->employers;
        usort($sorted, static fn($a, $b) => $b['rating'] <=> $a['rating']);
        return array_slice($sorted, 0, $limit);
    }
}

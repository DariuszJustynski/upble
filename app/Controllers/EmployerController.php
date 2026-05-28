<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\View;
use App\Repositories\JsonEmployerRepository;
use App\Repositories\JsonOfferRepository;

class EmployerController
{
    public function __construct(private readonly View $view) {}

    /** GET /employers/{id} */
    public function show(array $params = []): void
    {
        $employerRepo = new JsonEmployerRepository();
        $offerRepo    = new JsonOfferRepository();

        $employer = $employerRepo->find($params['id']);

        if ($employer === null) {
            http_response_code(404);
            $this->view->render('pages/error', [
                'page_title' => '404 Not Found — Upble',
                'message'    => 'Employer not found.',
            ]);
            return;
        }

        $offers  = $offerRepo->byEmployer($params['id']);
        $reviews = $this->loadReviews($params['id']);

        $this->view->render('pages/employer-show', [
            'page_title' => $employer['name'] . ' — Upble',
            'employer'   => $employer,
            'offers'     => $offers,
            'reviews'    => $reviews,
        ]);
    }

    private function loadReviews(string $employerId): array
    {
        $file = DATA . '/reviews.json';
        if (!is_file($file)) {
            return [];
        }
        $all = json_decode((string) file_get_contents($file), true, flags: JSON_THROW_ON_ERROR) ?? [];
        return array_values(array_filter(
            $all,
            static fn(array $r): bool => (string) $r['employer_id'] === $employerId
        ));
    }
}

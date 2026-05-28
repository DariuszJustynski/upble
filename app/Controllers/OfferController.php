<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\View;
use App\Repositories\JsonEmployerRepository;
use App\Repositories\JsonOfferRepository;

class OfferController
{
    public function __construct(private readonly View $view) {}

    /** GET /offers */
    public function index(array $params = []): void
    {
        $repo = new JsonOfferRepository();

        // Simple search support: /offers?q=…
        $query  = trim((string) ($_GET['q'] ?? ''));
        $offers = $query !== '' ? $repo->search($query) : $repo->all();

        $this->view->render('pages/offers', [
            'page_title'   => 'All Offers — Upble',
            'offers'       => $offers,
            'search_query' => $query,
        ]);
    }

    /** GET /offers/{id} */
    public function show(array $params = []): void
    {
        $offerRepo    = new JsonOfferRepository();
        $employerRepo = new JsonEmployerRepository();

        $offer = $offerRepo->find($params['id']);

        if ($offer === null) {
            http_response_code(404);
            $this->view->render('pages/error', [
                'page_title' => '404 Not Found — Upble',
                'message'    => 'Offer not found.',
            ]);
            return;
        }

        $employer = $employerRepo->find((string) $offer['employer_id']);

        $this->view->render('pages/offer-show', [
            'page_title' => $offer['title'] . ' — Upble',
            'offer'      => $offer,
            'employer'   => $employer,
        ]);
    }
}

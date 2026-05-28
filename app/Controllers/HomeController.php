<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\View;
use App\Repositories\JsonEmployerRepository;
use App\Repositories\JsonOfferRepository;

class HomeController
{
    public function __construct(private readonly View $view) {}

    public function index(array $params = []): void
    {
        $offers    = new JsonOfferRepository();
        $employers = new JsonEmployerRepository();

        $this->view->render('pages/home', [
            'page_title'    => 'Upble — Find Work &amp; Adventure Abroad',
            'latest_offers' => $offers->latest(6),
            'top_employers' => $employers->topRated(5),
        ]);
    }
}

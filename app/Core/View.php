<?php
declare(strict_types=1);

namespace App\Core;

/**
 * Lightweight template renderer.
 *
 * All data is available as plain PHP variables inside templates.
 * The $view instance itself is also injected so helpers like e() and
 * partial() are always accessible.
 *
 * Usage in a controller:
 *   $this->view->render('pages/home', ['offers' => $offers]);
 *
 * Usage in a template (XSS-safe output):
 *   <?= $view->e($offer['title']) ?>
 *
 * Rendering a reusable partial:
 *   <?= $view->partial('components/offer-card', ['offer' => $offer]) ?>
 */
class View
{
    private string $layout = 'layouts/main';

    /** Variables shared across every render call (e.g. site name). */
    private array $shared = [];

    // ── Public API ────────────────────────────────────────────────────────

    public function share(string $key, mixed $value): void
    {
        $this->shared[$key] = $value;
    }

    /**
     * Render a page template wrapped in the main layout.
     * $content is automatically populated with the inner template output.
     */
    public function render(string $template, array $data = []): void
    {
        $data    = array_merge($this->shared, $data);
        $content = $this->capture($template, $data);
        $this->output($this->layout, array_merge($data, ['content' => $content]));
    }

    /**
     * Render a partial template and return its HTML string.
     * Safe to call with echo inside another template.
     */
    public function partial(string $template, array $data = []): string
    {
        return $this->capture($template, array_merge($this->shared, $data));
    }

    // ── Helpers (available in every template as $view->…) ─────────────────

    /**
     * Escape a value for safe HTML output.  Always use this when printing
     * user-supplied or data-layer strings.
     */
    public function e(mixed $value): string
    {
        return htmlspecialchars((string) $value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    }

    /**
     * Format a date string or timestamp.
     */
    public function date(string|int $date, string $format = 'j M Y'): string
    {
        $ts = is_int($date) ? $date : strtotime($date);
        return date($format, $ts ?: 0);
    }

    /**
     * Build a star-rating string (e.g. "★★★★☆").
     */
    public function stars(float $rating, int $max = 5): string
    {
        $full  = min((int) round($rating), $max);
        $empty = $max - $full;
        return str_repeat('★', $full) . str_repeat('☆', $empty);
    }

    // ── Internals ─────────────────────────────────────────────────────────

    private function capture(string $template, array $data): string
    {
        ob_start();
        $this->output($template, $data);
        return (string) ob_get_clean();
    }

    private function output(string $template, array $data): void
    {
        $file = TEMPLATES . '/' . ltrim($template, '/') . '.php';

        if (!is_file($file)) {
            throw new \RuntimeException("Template not found: {$file}");
        }

        // Inject all data variables AND the $view helper into the template scope
        extract($data, EXTR_SKIP);
        $view = $this;          // always available as $view inside every template
        require $file;
    }
}

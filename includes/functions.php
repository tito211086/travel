<?php
/** Escape for HTML output. */
function e(?string $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

/** Build a site URL. */
function url(string $path = '', array $query = []): string
{
    $u = BASE_URL . '/' . ltrim($path, '/');
    return $query ? $u . '?' . http_build_query($query) : $u;
}

function asset(string $path): string
{
    $file = __DIR__ . '/../assets/' . ltrim($path, '/');
    $v = is_file($file) ? filemtime($file) : 1;
    return url('assets/' . ltrim($path, '/')) . '?v=' . $v;
}

function money(float $amount, string $currency = 'USD'): string
{
    $symbols = ['USD' => '$', 'EUR' => '€', 'GBP' => '£', 'INR' => '₹'];
    return ($symbols[$currency] ?? $currency . ' ') . number_format($amount, 0);
}

function is_current(string $page): bool
{
    return basename($_SERVER['SCRIPT_NAME'] ?? '') === $page;
}

/* ---------- CSRF ---------- */
function csrf_token(): string
{
    if (empty($_SESSION['csrf'])) {
        $_SESSION['csrf'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf'];
}

function csrf_field(): string
{
    return '<input type="hidden" name="csrf" value="' . e(csrf_token()) . '">';
}

function csrf_check(): bool
{
    return isset($_POST['csrf']) && is_string($_POST['csrf'])
        && hash_equals(csrf_token(), $_POST['csrf']);
}

/* ---------- Flash + redirects ---------- */
function flash(string $type, string $message): void
{
    $_SESSION['flash'][] = ['type' => $type, 'message' => $message];
}

function take_flashes(): array
{
    $f = $_SESSION['flash'] ?? [];
    unset($_SESSION['flash']);
    return $f;
}

function redirect(string $to): never
{
    header('Location: ' . $to, true, 303);
    exit;
}

/** Keep submitted values around after a failed POST/redirect. */
function old(string $key, string $default = ''): string
{
    return (string) ($_SESSION['old'][$key] ?? $default);
}

function clear_old(): void
{
    unset($_SESSION['old']);
}

/** Simple honeypot: bots fill hidden "website" field. */
function is_bot(): bool
{
    return !empty($_POST['website']);
}

function post_str(string $key, int $max = 255): string
{
    $v = isset($_POST[$key]) && is_string($_POST[$key]) ? trim($_POST[$key]) : '';
    return mb_substr($v, 0, $max);
}

function not_found(): never
{
    http_response_code(404);
    require __DIR__ . '/../404.php';
    exit;
}

/* ---------- Data helpers ---------- */
function get_destinations(?int $limit = null, bool $featuredOnly = false, string $region = ''): array
{
    $sql = 'SELECT d.*, (SELECT COUNT(*) FROM packages p WHERE p.destination_id = d.id AND p.is_active = 1) AS package_count
            FROM destinations d WHERE d.is_active = 1';
    $params = [];
    if ($featuredOnly) {
        $sql .= ' AND d.is_featured = 1';
    }
    if ($region !== '') {
        $sql .= ' AND d.region = ?';
        $params[] = $region;
    }
    $sql .= ' ORDER BY d.sort_order, d.name';
    if ($limit) {
        $sql .= ' LIMIT ' . (int) $limit;
    }
    $st = db()->prepare($sql);
    $st->execute($params);
    return $st->fetchAll();
}

function get_regions(): array
{
    return db()->query('SELECT DISTINCT region FROM destinations WHERE is_active = 1 ORDER BY region')
        ->fetchAll(PDO::FETCH_COLUMN);
}

function get_packages(array $filters = [], ?int $limit = null): array
{
    $sql = 'SELECT p.*, d.name AS destination_name, d.slug AS destination_slug, d.country
            FROM packages p JOIN destinations d ON d.id = p.destination_id
            WHERE p.is_active = 1';
    $params = [];
    if (!empty($filters['destination_id'])) {
        $sql .= ' AND p.destination_id = ?';
        $params[] = (int) $filters['destination_id'];
    }
    if (!empty($filters['featured'])) {
        $sql .= ' AND p.is_featured = 1';
    }
    if (!empty($filters['q'])) {
        $sql .= ' AND (p.title LIKE ? OR d.name LIKE ? OR d.country LIKE ?)';
        $like = '%' . $filters['q'] . '%';
        array_push($params, $like, $like, $like);
    }
    if (!empty($filters['style'])) {
        $sql .= ' AND p.style = ?';
        $params[] = $filters['style'];
    }
    if (!empty($filters['max_price'])) {
        $sql .= ' AND p.price <= ?';
        $params[] = (float) $filters['max_price'];
    }
    if (!empty($filters['max_days'])) {
        $sql .= ' AND p.duration_days <= ?';
        $params[] = (int) $filters['max_days'];
    }
    $order = [
        'price_asc'  => 'p.price ASC',
        'price_desc' => 'p.price DESC',
        'duration'   => 'p.duration_days ASC',
    ][$filters['sort'] ?? ''] ?? 'p.is_featured DESC, p.rating DESC';
    $sql .= ' ORDER BY ' . $order;
    if ($limit) {
        $sql .= ' LIMIT ' . (int) $limit;
    }
    $st = db()->prepare($sql);
    $st->execute($params);
    return $st->fetchAll();
}

function get_styles(): array
{
    return db()->query('SELECT DISTINCT style FROM packages WHERE is_active = 1 ORDER BY style')
        ->fetchAll(PDO::FETCH_COLUMN);
}

function get_testimonials(int $limit = 3): array
{
    $st = db()->prepare('SELECT * FROM testimonials WHERE is_published = 1 ORDER BY created_at DESC LIMIT ' . (int) $limit);
    $st->execute();
    return $st->fetchAll();
}

function stars(float $rating): string
{
    $full = (int) round($rating);
    return '<span class="stars" aria-label="' . e(number_format($rating, 1)) . ' out of 5">'
        . str_repeat('★', $full) . '<span class="stars-dim">' . str_repeat('★', 5 - $full) . '</span></span>';
}

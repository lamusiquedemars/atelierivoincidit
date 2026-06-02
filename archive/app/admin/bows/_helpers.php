<?php
/**
 * Helpers du module admin Archets.
 *
 * Objectif : centraliser les listes, la normalisation des valeurs
 * et éviter de dupliquer la logique entre create/edit/store/update.
 */

function admin_h($value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function fetch_options(PDO $pdo, string $table): array
{
    $allowedTables = [
        'instrument',
        'style',
        'shape',
        'wood',
        'color',
        'material',
        'size',
        'origin',
        'garnish',
        'range',
    ];

    if (!in_array($table, $allowedTables, true)) {
        throw new RuntimeException('Table de liste non autorisée.');
    }

    $stmt = $pdo->query("SELECT id, name FROM `{$table}` ORDER BY name");

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function fetch_quality_options(PDO $pdo, string $type): array
{
    $stmt = $pdo->prepare('SELECT id, name FROM quality WHERE type = ? ORDER BY id');
    $stmt->execute([$type]);

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function load_bow_form_options(PDO $pdo): array
{
    return [
        'instruments' => fetch_options($pdo, 'instrument'),
        'styles' => fetch_options($pdo, 'style'),
        'shapes' => fetch_options($pdo, 'shape'),
        'woods' => fetch_options($pdo, 'wood'),
        'colors' => fetch_options($pdo, 'color'),
        'materials' => fetch_options($pdo, 'material'),
        'sizes' => fetch_options($pdo, 'size'),
        'origins' => fetch_options($pdo, 'origin'),
        'garnishes' => fetch_options($pdo, 'garnish'),
        'ranges' => fetch_options($pdo, 'range'),
        'flexibilities' => fetch_quality_options($pdo, 'flexibilité'),
        'responsiveness' => fetch_quality_options($pdo, 'réactivité'),
        'handlings' => fetch_quality_options($pdo, 'maniabilité'),
        'natural_pressures' => fetch_quality_options($pdo, 'pression naturelle'),
        'projections' => fetch_quality_options($pdo, 'projection'),
        'sustains' => fetch_quality_options($pdo, 'sustain'),
        'tones' => fetch_quality_options($pdo, 'timbre'),
        'articulations' => fetch_quality_options($pdo, 'articulation'),
    ];
}

function empty_bow(): array
{
    $bow = array_fill_keys(bow_editable_columns(), null);
    $bow['id'] = null;
    $bow['status'] = 'available';
    $bow['active'] = 1;

    return $bow;
}

function load_bow(PDO $pdo, int $id): array
{
    $stmt = $pdo->prepare('SELECT * FROM bow WHERE id = ? LIMIT 1');
    $stmt->execute([$id]);
    $bow = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$bow) {
        throw new RuntimeException('Archet introuvable.');
    }

    return $bow;
}

function bow_editable_columns(): array
{
    return [
        'code',
        'name',
        'range_id',
        'instrument_id',
        'style_id',
        'shape_id',
        'size_id',
        'wood_id',
        'origin_id',
        'color_id',
        'button_material_id',
        'frog_material_id',
        'slide_material_id',
        'tip_material_id',
        'garnish_id',
        'stick_length',
        'total_length',
        'stick_weight',
        'total_weight',
        'balance_point',
        'density',
        'speed',
        'elasticity',
        'frequency',
        'damping',
        'flexibility_id',
        'responsiveness_id',
        'handling_id',
        'natural_pressure_id',
        'projection_id',
        'sustain_id',
        'tone_id',
        'articulation_id',
        'short_trait',
        'notes',
        'status',
        'price',
        'discount',
        'active',
    ];
}

function nullable_text($value): ?string
{
    $value = trim((string) ($value ?? ''));

    return $value === '' ? null : $value;
}

function nullable_int($value): ?int
{
    $value = trim((string) ($value ?? ''));

    return $value === '' ? null : (int) $value;
}

function nullable_float($value): ?float
{
    $value = trim((string) ($value ?? ''));

    if ($value === '') {
        return null;
    }

    // Permet de saisir 12,5 ou 12.5 dans l’admin.
    $value = str_replace(',', '.', $value);

    return (float) $value;
}

function nullable_measure($value): ?float
{
    $number = nullable_float($value);

    // Pour les mesures physiques, 0 sert souvent de faux “non mesuré”.
    // On le transforme en NULL pour ne plus polluer la BDD.
    return $number === 0.0 ? null : $number;
}

function price_to_cents($value): ?int
{
    $price = nullable_float($value);

    if ($price === null) {
        return null;
    }

    return (int) round($price * 100);
}

function price_from_cents($value): string
{
    if ($value === null || $value === '') {
        return '';
    }

    return rtrim(rtrim(number_format(((int) $value) / 100, 2, '.', ''), '0'), '.');
}

function bow_payload_from_post(array $post): array
{
    $status = $post['status'] ?? 'available';
    $allowedStatuses = ['available', 'unavailable', 'sold'];

    if (!in_array($status, $allowedStatuses, true)) {
        $status = 'available';
    }

    return [
        'code' => nullable_text($post['code'] ?? null),
        'name' => nullable_text($post['name'] ?? null),
        'range_id' => nullable_int($post['range_id'] ?? null),
        'instrument_id' => nullable_int($post['instrument_id'] ?? null),
        'style_id' => nullable_int($post['style_id'] ?? null),
        'shape_id' => nullable_int($post['shape_id'] ?? null),
        'size_id' => nullable_int($post['size_id'] ?? null),
        'wood_id' => nullable_int($post['wood_id'] ?? null),
        'origin_id' => nullable_int($post['origin_id'] ?? null),
        'color_id' => nullable_int($post['color_id'] ?? null),
        'button_material_id' => nullable_int($post['button_material_id'] ?? null),
        'frog_material_id' => nullable_int($post['frog_material_id'] ?? null),
        'slide_material_id' => nullable_int($post['slide_material_id'] ?? null),
        'tip_material_id' => nullable_int($post['tip_material_id'] ?? null),
        'garnish_id' => nullable_int($post['garnish_id'] ?? null),
        'stick_length' => nullable_measure($post['stick_length'] ?? null),
        'total_length' => nullable_measure($post['total_length'] ?? null),
        'stick_weight' => nullable_measure($post['stick_weight'] ?? null),
        'total_weight' => nullable_measure($post['total_weight'] ?? null),
        'balance_point' => nullable_measure($post['balance_point'] ?? null),
        'density' => nullable_measure($post['density'] ?? null),
        'speed' => nullable_measure($post['speed'] ?? null),
        'elasticity' => nullable_measure($post['elasticity'] ?? null),
        'frequency' => nullable_measure($post['frequency'] ?? null),
        'damping' => nullable_measure($post['damping'] ?? null),
        'flexibility_id' => nullable_int($post['flexibility_id'] ?? null),
        'responsiveness_id' => nullable_int($post['responsiveness_id'] ?? null),
        'handling_id' => nullable_int($post['handling_id'] ?? null),
        'natural_pressure_id' => nullable_int($post['natural_pressure_id'] ?? null),
        'projection_id' => nullable_int($post['projection_id'] ?? null),
        'sustain_id' => nullable_int($post['sustain_id'] ?? null),
        'tone_id' => nullable_int($post['tone_id'] ?? null),
        'articulation_id' => nullable_int($post['articulation_id'] ?? null),
        'short_trait' => nullable_text($post['short_trait'] ?? null),
        'notes' => nullable_text($post['notes'] ?? null),
        'status' => $status,
        'price' => price_to_cents($post['price_eur'] ?? null),
        'discount' => nullable_int($post['discount'] ?? null),
        'active' => isset($post['active']) ? 1 : 0,
    ];
}

function validate_bow_payload(array $payload): array
{
    $errors = [];

    if ($payload['code'] === null) {
        $errors[] = 'Le code est obligatoire.';
    }

    if ($payload['discount'] !== null && ($payload['discount'] < 0 || $payload['discount'] > 100)) {
        $errors[] = 'La remise doit être comprise entre 0 et 100.';
    }

    return $errors;
}

function insert_bow(PDO $pdo, array $payload): int
{
    $columns = array_keys($payload);
    $placeholders = array_fill(0, count($columns), '?');

    $sql = 'INSERT INTO bow (`' . implode('`, `', $columns) . '`) VALUES (' . implode(', ', $placeholders) . ')';
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array_values($payload));

    return (int) $pdo->lastInsertId();
}

function update_bow(PDO $pdo, int $id, array $payload): void
{
    $columns = array_keys($payload);
    $assignments = array_map(static fn ($column) => "`{$column}` = ?", $columns);

    $sql = 'UPDATE bow SET ' . implode(', ', $assignments) . ' WHERE id = ?';
    $values = array_values($payload);
    $values[] = $id;

    $stmt = $pdo->prepare($sql);
    $stmt->execute($values);
}

function selected_attr($current, $value): string
{
    return (string) $current === (string) $value ? ' selected' : '';
}

function checked_attr($value): string
{
    return (int) $value === 1 ? ' checked' : '';
}

function number_value(array $bow, string $key): string
{
    $value = $bow[$key] ?? null;

    if ($value === null || $value === '' || (float) $value === 0.0) {
        return '';
    }

    return admin_h($value);
}

function render_select(string $name, array $items, $currentValue, string $emptyLabel = '(vide)'): void
{
    echo '<select name="' . admin_h($name) . '">';
    echo '<option value="">' . admin_h($emptyLabel) . '</option>';

    foreach ($items as $item) {
        $id = $item['id'];
        $label = $item['name'] ?? '';
        echo '<option value="' . admin_h($id) . '"' . selected_attr($currentValue, $id) . '>' . admin_h($label) . '</option>';
    }

    echo '</select>';
}

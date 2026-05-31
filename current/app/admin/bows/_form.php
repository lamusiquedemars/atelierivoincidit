<?php
/**
 * Formulaire commun création / modification d’un archet.
 *
 * Variables attendues :
 * - $bow
 * - $options
 * - $action
 * - $submitLabel
 */
?>

<form method="post" action="<?= admin_h($action) ?>">
    <?= csrf_field() ?>

    <?php if (!empty($bow['id'])): ?>
        <input type="hidden" name="id" value="<?= admin_h($bow['id']) ?>">
    <?php endif; ?>

    <section class="admin-card">
        <h2>Identification</h2>

        <div class="admin-grid">
            <div class="admin-field">
                <label for="code">Code</label>
                <input id="code" type="text" name="code" value="<?= admin_h($bow['code'] ?? '') ?>" required>
            </div>

            <div class="admin-field">
                <label for="name">Nom</label>
                <input id="name" type="text" name="name" value="<?= admin_h($bow['name'] ?? '') ?>">
            </div>

            <div class="admin-field">
                <label for="price_eur">Prix public (€)</label>
                <input id="price_eur" type="number" name="price_eur" min="0" step="0.01" value="<?= admin_h(price_from_cents($bow['price'] ?? null)) ?>">
            </div>

            <div class="admin-field">
                <label for="discount">Remise (%)</label>
                <input id="discount" type="number" name="discount" min="0" max="100" step="1" value="<?= admin_h($bow['discount'] ?? '') ?>">
            </div>

            <div class="admin-field">
                <label for="status">Statut</label>
                <select id="status" name="status">
                    <option value="available"<?= selected_attr($bow['status'] ?? 'available', 'available') ?>>Disponible</option>
                    <option value="unavailable"<?= selected_attr($bow['status'] ?? '', 'unavailable') ?>>Indisponible</option>
                    <option value="sold"<?= selected_attr($bow['status'] ?? '', 'sold') ?>>Vendu</option>
                </select>
            </div>

            <div class="admin-field">
                <label>
                    <input type="checkbox" name="active" value="1"<?= checked_attr($bow['active'] ?? 1) ?>>
                    Visible sur le site
                </label>
            </div>
        </div>
    </section>

    <section class="admin-card">
        <h2>Classification</h2>

        <div class="admin-grid">
            <div class="admin-field"><label>Gamme</label><?php render_select('range_id', $options['ranges'], $bow['range_id'] ?? null); ?></div>
            <div class="admin-field"><label>Instrument</label><?php render_select('instrument_id', $options['instruments'], $bow['instrument_id'] ?? null); ?></div>
            <div class="admin-field"><label>Style</label><?php render_select('style_id', $options['styles'], $bow['style_id'] ?? null); ?></div>
            <div class="admin-field"><label>Forme</label><?php render_select('shape_id', $options['shapes'], $bow['shape_id'] ?? null); ?></div>
            <div class="admin-field"><label>Taille</label><?php render_select('size_id', $options['sizes'], $bow['size_id'] ?? null); ?></div>
            <div class="admin-field"><label>Bois</label><?php render_select('wood_id', $options['woods'], $bow['wood_id'] ?? null); ?></div>
            <div class="admin-field"><label>Origine</label><?php render_select('origin_id', $options['origins'], $bow['origin_id'] ?? null); ?></div>
            <div class="admin-field"><label>Couleur</label><?php render_select('color_id', $options['colors'], $bow['color_id'] ?? null); ?></div>
        </div>
    </section>

    <section class="admin-card">
        <h2>Montage</h2>

        <div class="admin-grid">
            <div class="admin-field"><label>Matériau du bouton</label><?php render_select('button_material_id', $options['materials'], $bow['button_material_id'] ?? null); ?></div>
            <div class="admin-field"><label>Matériau de la hausse</label><?php render_select('frog_material_id', $options['materials'], $bow['frog_material_id'] ?? null); ?></div>
            <div class="admin-field"><label>Matériau de la coulisse</label><?php render_select('slide_material_id', $options['materials'], $bow['slide_material_id'] ?? null, '(aucun / vide)'); ?></div>
            <div class="admin-field"><label>Matériau de la pointe</label><?php render_select('tip_material_id', $options['materials'], $bow['tip_material_id'] ?? null); ?></div>
            <div class="admin-field"><label>Garniture</label><?php render_select('garnish_id', $options['garnishes'], $bow['garnish_id'] ?? null, '(aucune / vide)'); ?></div>
        </div>
    </section>

    <section class="admin-card">
        <h2>Mesures physiques</h2>

        <div class="admin-grid">
            <div class="admin-field"><label>Longueur baguette (mm)</label><input type="number" name="stick_length" step="0.1" value="<?= number_value($bow, 'stick_length') ?>"></div>
            <div class="admin-field"><label>Longueur totale (mm)</label><input type="number" name="total_length" step="0.1" value="<?= number_value($bow, 'total_length') ?>"></div>
            <div class="admin-field"><label>Poids baguette (g)</label><input type="number" name="stick_weight" step="0.01" value="<?= number_value($bow, 'stick_weight') ?>"></div>
            <div class="admin-field"><label>Poids total (g)</label><input type="number" name="total_weight" step="0.01" value="<?= number_value($bow, 'total_weight') ?>"></div>
            <div class="admin-field"><label>Équilibre (mm)</label><input type="number" name="balance_point" step="0.1" value="<?= number_value($bow, 'balance_point') ?>"></div>
            <div class="admin-field"><label>Densité (kg/m³)</label><input type="number" name="density" step="0.1" value="<?= number_value($bow, 'density') ?>"></div>
            <div class="admin-field"><label>Vitesse du son (m/s)</label><input type="number" name="speed" step="0.1" value="<?= number_value($bow, 'speed') ?>"></div>
            <div class="admin-field"><label>Élasticité (GPa)</label><input type="number" name="elasticity" step="0.1" value="<?= number_value($bow, 'elasticity') ?>"></div>
            <div class="admin-field"><label>Fréquence (Hz)</label><input type="number" name="frequency" step="0.1" value="<?= number_value($bow, 'frequency') ?>"></div>
            <div class="admin-field"><label>Amortissement δ</label><input type="number" name="damping" step="0.0001" value="<?= number_value($bow, 'damping') ?>"></div>
        </div>
    </section>

    <section class="admin-card">
        <h2>Caractère de jeu</h2>

        <div class="admin-grid">
            <div class="admin-field"><label>Flexibilité</label><?php render_select('flexibility_id', $options['flexibilities'], $bow['flexibility_id'] ?? null); ?></div>
            <div class="admin-field"><label>Réactivité</label><?php render_select('responsiveness_id', $options['responsiveness'], $bow['responsiveness_id'] ?? null); ?></div>
            <div class="admin-field"><label>Maniabilité</label><?php render_select('handling_id', $options['handlings'], $bow['handling_id'] ?? null); ?></div>
            <div class="admin-field"><label>Pression naturelle</label><?php render_select('natural_pressure_id', $options['natural_pressures'], $bow['natural_pressure_id'] ?? null); ?></div>
            <div class="admin-field"><label>Projection</label><?php render_select('projection_id', $options['projections'], $bow['projection_id'] ?? null); ?></div>
            <div class="admin-field"><label>Sustain</label><?php render_select('sustain_id', $options['sustains'], $bow['sustain_id'] ?? null); ?></div>
            <div class="admin-field"><label>Timbre</label><?php render_select('tone_id', $options['tones'], $bow['tone_id'] ?? null); ?></div>
            <div class="admin-field"><label>Articulation</label><?php render_select('articulation_id', $options['articulations'], $bow['articulation_id'] ?? null); ?></div>
        </div>
    </section>

    <section class="admin-card">
        <h2>Texte interne / affichage</h2>

        <div class="admin-field">
            <label for="short_trait">Trait court</label>
            <input id="short_trait" type="text" name="short_trait" value="<?= admin_h($bow['short_trait'] ?? '') ?>">
        </div>

        <div class="admin-field">
            <label for="notes">Notes</label>
            <textarea id="notes" name="notes" rows="6"><?= admin_h($bow['notes'] ?? '') ?></textarea>
        </div>
    </section>

    <p>
        <button type="submit"><?= admin_h($submitLabel) ?></button>
        <a href="<?= admin_h(admin_url('bows')) ?>">Annuler</a>
    </p>
</form>

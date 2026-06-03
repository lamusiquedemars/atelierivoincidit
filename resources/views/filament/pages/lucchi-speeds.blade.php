<x-filament-panels::page>
    <div class="space-y-6">
        <div class="rounded-lg border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-800 dark:bg-gray-900">
            <h2 class="text-base font-semibold text-gray-950 dark:text-white">Introduction et signification des colonnes</h2>
            <p class="mt-3 text-sm leading-6 text-gray-700 dark:text-gray-300">
                Ce tableau présente les vitesses longitudinales obtenues par la méthode micro + Audacity, puis ajustées pour tenir compte de la masse de la tête et converties vers les valeurs comparables aux mesures Lucchi.
            </p>
            <dl class="mt-4 grid gap-3 text-sm leading-6 text-gray-700 dark:text-gray-300 md:grid-cols-2">
                <div><dt class="font-medium text-gray-950 dark:text-white">Longueur (mm)</dt><dd>Longueur vibrante utile de la baguette.</dd></div>
                <div><dt class="font-medium text-gray-950 dark:text-white">Fréquence (Hz)</dt><dd>Fréquence fondamentale relevée dans Audacity.</dd></div>
                <div><dt class="font-medium text-gray-950 dark:text-white">V brute (m/s)</dt><dd><code>v_brute = 2 · L · f</code></dd></div>
                <div><dt class="font-medium text-gray-950 dark:text-white">V corrigée (m/s)</dt><dd><code>v_corr = v_brute · (1 + 0.73 · f_tête)</code></dd></div>
                <div><dt class="font-medium text-gray-950 dark:text-white">V Lucchi (m/s)</dt><dd><code>v_Lucchi = v_corr · 1.061</code></dd></div>
            </dl>
        </div>

        <div class="overflow-hidden rounded-lg border border-gray-200 bg-white shadow-sm dark:border-gray-800 dark:bg-gray-900">
            <div class="max-h-[70vh] overflow-auto">
                <table class="min-w-full divide-y divide-gray-200 text-sm dark:divide-gray-800">
                    <thead class="sticky top-0 bg-gray-50 dark:bg-gray-950">
                        <tr>
                            <th class="px-4 py-3 text-right font-semibold text-gray-950 dark:text-white">Longueur (mm)</th>
                            <th class="px-4 py-3 text-right font-semibold text-gray-950 dark:text-white">Fréquence (Hz)</th>
                            <th class="px-4 py-3 text-right font-semibold text-gray-950 dark:text-white">V brute (m/s)</th>
                            <th class="px-4 py-3 text-right font-semibold text-gray-950 dark:text-white">V corrigée (m/s)</th>
                            <th class="px-4 py-3 text-right font-semibold text-gray-950 dark:text-white">V Lucchi (m/s)</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                        @foreach ($this->rows() as $row)
                            <tr>
                                <td class="px-4 py-2 text-right">{{ $row['length'] }}</td>
                                <td class="px-4 py-2 text-right">{{ $row['frequency'] }}</td>
                                <td class="px-4 py-2 text-right">{{ $row['raw_speed'] }}</td>
                                <td class="px-4 py-2 text-right">{{ $row['corrected_speed'] }}</td>
                                <td class="px-4 py-2 text-right font-medium">{{ $row['lucchi_speed'] }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-filament-panels::page>

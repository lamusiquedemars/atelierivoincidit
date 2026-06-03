<x-filament-panels::page>
    <div class="space-y-6">
        <x-filament::section>
            <p class="text-sm leading-6 text-gray-700 dark:text-gray-300">
                Cet outil parcourt récursivement <code>public/assets/images</code> et redimensionne les JPEG/PNG dont la largeur dépasse 1600 px.
            </p>
            <p class="mt-3 text-sm leading-6 text-gray-700 dark:text-gray-300">
                Le traitement s’enchaîne automatiquement après un seul clic. Chaque requête dispose d’un timeout PHP porté à 300 secondes.
            </p>
        </x-filament::section>

        <x-filament::section>
            <div class="flex flex-wrap items-center justify-between gap-4">
                <div>
                    <p class="text-sm font-medium text-gray-950 dark:text-white">
                        @if ($isOptimizing)
                            Optimisation en cours
                        @elseif ($remaining > 0)
                            {{ $remaining }} image(s) à optimiser
                        @else
                            Aucune image trop large à optimiser
                        @endif
                    </p>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                        {{ $totalResized }} redimensionnée(s), {{ $totalChecked }} traitée(s), {{ $totalErrors }} erreur(s).
                    </p>
                </div>

                <x-filament::badge color="warning">
                    {{ $this->progressPercent() }}%
                </x-filament::badge>
            </div>

            <div class="mt-5 h-3 overflow-hidden rounded-full bg-gray-100 dark:bg-gray-800">
                <div
                    class="h-full rounded-full bg-warning-600 transition-all duration-500"
                    style="width: {{ $this->progressPercent() }}%"
                ></div>
            </div>

            @if ($isOptimizing)
                <div wire:poll.1500ms="processNextBatch" class="mt-4 text-sm text-gray-600 dark:text-gray-400">
                    Traitement automatique du prochain lot…
                </div>
            @elseif ($remaining === 0)
                <div class="mt-4 text-sm text-success-700 dark:text-success-300">
                    Toutes les images trop larges sont traitées.
                </div>
            @endif
        </x-filament::section>

        @if ($optimizedImages !== [])
            <div class="fi-ta">
                <div class="fi-ta-ctn fi-ta-ctn-with-header">
                    <div class="fi-ta-main">
                        <div class="fi-ta-header-ctn">
                            <div class="fi-ta-header">
                                <div>
                                    <h3 class="fi-ta-header-heading">Images optimisées</h3>
                                    <p class="fi-ta-header-description">
                                        Détail des fichiers modifiés pendant cette optimisation.
                                    </p>
                                </div>

                                <x-filament::badge color="gray">
                                    {{ count($optimizedImages) }} fichier(s)
                                </x-filament::badge>
                            </div>
                        </div>

                        <div class="fi-ta-content-ctn fi-fixed-positioning-context">
                            <div class="fi-ta-table-scroll-ctn overflow-x-auto">
                                <table class="fi-ta-table">
                                    <thead>
                                        <tr>
                                            <th class="fi-ta-header-cell">Image</th>
                                            <th class="fi-ta-header-cell fi-align-end">Dimensions avant</th>
                                            <th class="fi-ta-header-cell fi-align-end">Dimensions après</th>
                                            <th class="fi-ta-header-cell fi-align-end">Poids avant</th>
                                            <th class="fi-ta-header-cell fi-align-end">Poids après</th>
                                            <th class="fi-ta-header-cell fi-align-end">Ratio</th>
                                            <th class="fi-ta-header-cell fi-align-end">Gain</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($optimizedImages as $image)
                                            <tr class="fi-ta-row">
                                                <td class="fi-ta-cell">
                                                    <div class="fi-ta-text font-mono text-xs" title="{{ $image['path'] }}">
                                                        {{ $image['path'] }}
                                                    </div>
                                                </td>
                                                <td class="fi-ta-cell fi-align-end">
                                                    {{ $image['before_width'] }} x {{ $image['before_height'] }}
                                                </td>
                                                <td class="fi-ta-cell fi-align-end">
                                                    {{ $image['after_width'] }} x {{ $image['after_height'] }}
                                                </td>
                                                <td class="fi-ta-cell fi-align-end">
                                                    {{ $this->formatBytes((int) $image['before_size']) }}
                                                </td>
                                                <td class="fi-ta-cell fi-align-end">
                                                    {{ $this->formatBytes((int) $image['after_size']) }}
                                                </td>
                                                <td class="fi-ta-cell fi-align-end">
                                                    <x-filament::badge color="gray">
                                                        {{ number_format(((float) $image['size_ratio']) * 100, 1, ',', ' ') }} %
                                                    </x-filament::badge>
                                                </td>
                                                <td class="fi-ta-cell fi-align-end">
                                                    <x-filament::badge color="success">
                                                        -{{ number_format((float) $image['reduction_percent'], 1, ',', ' ') }} %
                                                    </x-filament::badge>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</x-filament-panels::page>

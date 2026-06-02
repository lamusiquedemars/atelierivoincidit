@extends('layouts.site', [
    'seoTitle' => config('maracuja.theme') === 'atelier' ? 'Contact' : 'Contact - ' . $settings->site_name,
    'seoDescription' => config('maracuja.theme') === 'atelier'
        ? 'Contacter l’atelier Ivo Incidit pour un conseil, un essai ou une demande d’archet.'
        : 'Contacter ' . $settings->site_name,
])

@section('content')
    @php($isAtelier = config('maracuja.theme') === 'atelier')

    <x-site.hero
        :eyebrow="$isAtelier ? 'Contact' : 'Maracuja CMS'"
        :title="$isAtelier ? 'Essayer un archet' : 'Contact'"
        :subtitle="$isAtelier ? 'Contactez-moi pour un essai, un conseil, ou choisir un archet adapté à votre jeu.' : 'Un formulaire simple, stocké en admin et envoyé par email.'"
        :variant="$isAtelier ? 'contact' : 'page'"
    />

    @if ($isAtelier)
        <x-site.breadcrumb :items="[['label' => 'Contact']]" />

        <x-site.section title="Pourquoi me contacter ?" heading-variant="accent">
            <div class="prose">
                <p>
                    Il n’est pas nécessaire de savoir exactement quel archet choisir avant d’écrire.
                    Vous pouvez simplement me décrire votre instrument, votre jeu, votre budget,
                    ou l’archet qui vous intéresse.
                </p>
            </div>
            <x-site.grid columns="3">
                <x-site.feature-card title="Essayer un archet" icon="01">
                    Organiser un essai à l’atelier, près de Lyon, ou envisager un envoi lorsque c’est possible.
                </x-site.feature-card>
                <x-site.feature-card title="Demander conseil" icon="02">
                    Choisir entre Ars Antiqua, Ars Classica et Ars Nova, ou mieux cerner le type d’archet adapté à votre jeu.
                </x-site.feature-card>
                <x-site.feature-card title="Parler d’une commande" icon="03">
                    Évoquer une fabrication à venir, une recherche particulière, ou un archet qui n’est pas actuellement disponible.
                </x-site.feature-card>
            </x-site.grid>
        </x-site.section>
    @endif

    <x-site.section inner-class="contact-layout">
        @if ($isAtelier)
            <div class="contact-main prose">
                <h2>Écrire à l’atelier</h2>
                <p>
                    Le plus simple est de m’envoyer un message en précisant votre instrument,
                    votre niveau ou pratique musicale, et ce que vous cherchez à améliorer
                    ou à retrouver dans un archet.
                </p>
                <p>
                    Si vous avez déjà repéré un archet sur le site, indiquez simplement son nom
                    ou son numéro.
                </p>
                @if ($settings->contact_email)
                    <p>
                        Adresse directe :
                        <a href="mailto:{{ $settings->contact_email }}">{{ $settings->contact_email }}</a>
                    </p>
                @endif

                @if (session('status'))
                    <p class="notice">{{ session('status') }}</p>
                @endif

                <form method="post" action="{{ route('contact.store') }}" class="contact-form" data-form>
                    @csrf
                    <input type="text" name="website" value="" autocomplete="off" tabindex="-1" style="position:absolute; left:-9999px; top:auto; width:1px; height:1px; overflow:hidden;">

                    @if ($settings->contact_form_show_name)
                        <label>
                            Nom
                            <input name="name" value="{{ old('name') }}" required>
                            @error('name') <small>{{ $message }}</small> @enderror
                        </label>
                    @endif

                    <label>
                        Email
                        <input name="email" type="email" value="{{ old('email') }}" required>
                        @error('email') <small>{{ $message }}</small> @enderror
                    </label>

                    @if ($settings->contact_form_show_phone)
                        <label>
                            Téléphone
                            <input name="phone" value="{{ old('phone') }}">
                        </label>
                    @endif

                    @if ($settings->contact_form_show_subject)
                        <label>
                            Sujet
                            <input name="subject" value="{{ old('subject') }}">
                        </label>
                    @endif

                    <label class="full">
                        Message
                        <textarea name="message" rows="7" required>{{ old('message') }}</textarea>
                        @error('message') <small>{{ $message }}</small> @enderror
                    </label>
                    <x-site.button type="submit">Envoyer</x-site.button>
                </form>
            </div>

            <div class="contact-visual">
                <x-site.figure
                    src="/assets/images/ivo-correia.HEIC"
                    alt="Ivo Correia de Melo, archetier près de Lyon"
                />
            </div>
        @else
            @if (session('status'))
                <p class="notice">{{ session('status') }}</p>
            @endif

            <form method="post" action="{{ route('contact.store') }}" class="contact-form" data-form>
                @csrf
                <input type="text" name="website" value="" autocomplete="off" tabindex="-1" style="position:absolute; left:-9999px; top:auto; width:1px; height:1px; overflow:hidden;">

                @if ($settings->contact_form_show_name)
                    <label>
                        Nom
                        <input name="name" value="{{ old('name') }}" required>
                        @error('name') <small>{{ $message }}</small> @enderror
                    </label>
                @endif

                <label>
                    Email
                    <input name="email" type="email" value="{{ old('email') }}" required>
                    @error('email') <small>{{ $message }}</small> @enderror
                </label>

                @if ($settings->contact_form_show_phone)
                    <label>
                        Téléphone
                        <input name="phone" value="{{ old('phone') }}">
                    </label>
                @endif

                @if ($settings->contact_form_show_subject)
                    <label>
                        Sujet
                        <input name="subject" value="{{ old('subject') }}">
                    </label>
                @endif

                <label class="full">
                    Message
                    <textarea name="message" rows="7" required>{{ old('message') }}</textarea>
                    @error('message') <small>{{ $message }}</small> @enderror
                </label>
                <x-site.button type="submit">Envoyer</x-site.button>
            </form>
        @endif
    </x-site.section>

    @if ($isAtelier)
        <x-site.section variant="surface" title="Essai et envoi" heading-variant="underline">
            <div class="prose">
                <p>
                    Les archets peuvent être essayés à l’atelier, à Collonges-au-Mont-d’Or,
                    près de Lyon. Un essai par envoi peut aussi être envisagé selon les cas.
                </p>
                <p>
                    Les envois sont réalisés via Colissimo suivi. Les frais et délais ci-dessous
                    sont donnés à titre indicatif.
                </p>
            </div>
            <table class="table">
                <thead>
                    <tr>
                        <th>Destination</th>
                        <th>Délais estimés</th>
                        <th>Frais d’envoi</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>France</td>
                        <td>2 à 5 jours</td>
                        <td>12 €</td>
                    </tr>
                    <tr>
                        <td>Europe</td>
                        <td>3 à 7 jours</td>
                        <td>20 €</td>
                    </tr>
                </tbody>
            </table>
            <div class="prose">
                <p>
                    Les conditions détaillées d’essai, d’expédition, de paiement et de garantie
                    sont précisées dans les
                    <a href="{{ route('atelier.terms') }}">Conditions Générales de Vente</a>.
                </p>
            </div>
        </x-site.section>

        <x-site.section title="Informations atelier" heading-variant="accent">
            <div class="prose">
                <p><strong>Atelier :</strong> Ivo Incidit</p>
                <p><strong>Artisan :</strong> Ivo Correia de Melo</p>
                <p><strong>Lieu :</strong> Collonges-au-Mont-d’Or, près de Lyon</p>
                <p>
                    <strong>Entreprise :</strong>
                    Entreprise individuelle immatriculée au Répertoire des Métiers
                    nº 894 976 133 RM 69.
                </p>
            </div>
        </x-site.section>
    @endif
@endsection

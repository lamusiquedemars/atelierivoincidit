<?php

namespace App\Support;

class AtelierHomeContent
{
    public static function showcase(): array
    {
        return [
            [
                'title' => 'Hausses d’archets',
                'text' => 'Hausses modernes et baroques.',
                'image' => '/assets/images/showcase-hausses.jpeg',
                'alt' => 'Hausses d’archets',
            ],
            [
                'title' => 'Archets de violoncelle',
                'text' => 'Tête et hausse d’archet, en ipé et en cumaru.',
                'image' => '/assets/images/showcase-c-1.jpeg',
                'alt' => 'Archets de violoncelle',
            ],
            [
                'title' => 'Tête d’archet de violon',
                'text' => 'Archet de violon en amarante, plaque de tête en bois satiné.',
                'image' => '/assets/images/showcase-v-1.jpeg',
                'alt' => 'Tête d’archet de violon',
            ],
            [
                'title' => 'Archet baroque de violoncelle',
                'text' => 'Archet baroque de violoncelle en cumaru, hausse et bouton en amourette.',
                'image' => '/assets/images/showcase-cb-1.jpeg',
                'alt' => 'Archet baroque de violoncelle',
            ],
            [
                'title' => 'Archet de violon pour enfant',
                'text' => 'Archet de violon pour enfant en cumaru blond, garniture en fil de lin coloré.',
                'image' => '/assets/images/showcase-ve-1.jpeg',
                'alt' => 'Archet de violon pour enfant',
            ],
            [
                'title' => 'Têtes d’archet de violon',
                'text' => 'Archets de violon en cumaru et wamara, plaques de tête en ébène et en os.',
                'image' => '/assets/images/showcase-v-4.jpeg',
                'alt' => 'Têtes d’archet de violon',
            ],
        ];
    }

    public static function quotes(): array
    {
        return [
            [
                'quote' => 'L’archet donne un sentiment de liberté, de légèreté, d’équilibre : pas besoin de forcer.',
                'author' => 'Lucas Leblanc',
                'meta' => 'violoncelliste, étudiant au conservatoire de Lyon',
            ],
            [
                'quote' => 'J’ai beaucoup aimé l’archet ; la garniture colorée m’a beaucoup plu.',
                'author' => 'Marie Leloup',
                'meta' => 'violoniste, fondatrice de jouerduviolon.com',
            ],
            [
                'quote' => 'L’archet a de jolis détails, exactement comme je souhaitais. Il est très maniable et convient à ma taille.',
                'author' => 'Simon Segura',
                'meta' => 'étudiant de violon, Munich',
            ],
        ];
    }
}

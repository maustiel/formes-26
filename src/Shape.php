<?php

declare(strict_types=1);

namespace Shapes;

/**
 * ÉTAPE 2 — Ce que toutes les formes ont en commun : une couleur, et une aire.
 *
 * La classe est `abstract` : `new Shape('#FF0000')` doit être impossible.
 * Une forme sans forme, ça n'existe pas.
 *
 * Le constructeur reçoit la couleur (`#RRGGBB`), la valide et la met en
 * majuscules avant de la ranger dans `public readonly string $color`.
 * Une couleur invalide lève `\InvalidArgumentException`. Pas de `color()` :
 * on lit `$shape->color`.
 *
 * Attention : une propriété `readonly` ne s'écrit qu'une fois. Ne la promouvez
 * pas dans la signature si vous devez la transformer : déclarez-la à part et
 * affectez-la dans le corps, après validation.
 *
 * Indice pour la validation : `preg_match('/^#[0-9A-Fa-f]{6}$/', $color)`.
 */
abstract class Shape
{
    public const string DEFAULT_COLOR = '#000000';

    /** TODO : `public readonly string $color;` déclarée ici, remplie par le constructeur. */
    public readonly string $color;
    // TODO : la validation, puis `$this->color = strtoupper($color);`.
    public function __construct(string $color = self::DEFAULT_COLOR)
    {
        if (preg_match('/^#[0-9A-Fa-f]{6}$/', $color) !== 1) {
            throw new \InvalidArgumentException("Couleur invalide : {$color}");
        }

        $this->color = strtoupper($color);
    }

    abstract public function area(): float;
}

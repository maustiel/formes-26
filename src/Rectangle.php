<?php

declare(strict_types=1);

namespace Shapes;

/**
 * ÉTAPE 2 — Un rectangle : son coin supérieur gauche, une largeur, une hauteur.
 *
 * Constructeur attendu :
 * `new Rectangle(Point $origin, float $width, float $height, string $color = '#000000')`.
 * Doit exposer `public readonly Point $origin`, `public readonly float $width`
 * et `public readonly float $height` (promotion de constructeur).
 * Une dimension nulle ou négative lève `\InvalidArgumentException`.
 */
final class Rectangle extends Shape
{
    // TODO : promouvoir les trois propriétés en `public readonly`, valider les dimensions.
    public function __construct(
       public readonly Point $origin, 
       public readonly float $width, 
       public readonly float $height, 
        string $color = self::DEFAULT_COLOR)
    {
          if ($width <= 0) {
            throw new \InvalidArgumentException("longueur invalide : {$width}");
        }
        if ($height <= 0) {
            throw new \InvalidArgumentException("hauteur invalide : {$height}");
        }
        parent::__construct($color);
    }

    /** TODO : 2 × (largeur + hauteur). */
    public function perimeter(): float
    {
        return 2 * ($this->width + $this->height);
    }

    public function area(): float
    {
        return $this->width * $this->height;
    }
}

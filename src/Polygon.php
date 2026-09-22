<?php

declare(strict_types=1);

namespace Shapes;

/**
 * ÉTAPE 3 — Une ligne brisée fermée : trois sommets au minimum.
 *
 * Constructeur attendu : `new Polygon(array $points, string $color = '#000000')`.
 * Moins de trois sommets, ou un élément qui n'est pas un `Point` :
 * `\InvalidArgumentException`.
 *
 * Les sommets sont exposés en `public readonly array $points` : on lit
 * `$polygon->points`, pas de `points()`. `pointCount()` reste une méthode.
 *
 * Le type `array` de PHP ne dit pas ce qu'il contient : c'est à vous de
 * vérifier, avec `instanceof`.
 */
final class Polygon extends Shape
{
    /** @var list<Point> Les sommets, dans l'ordre. */
    public readonly array $points;

    /** @param list<Point> $points */
    public function __construct (
        array $points, 
        string $color = self::DEFAULT_COLOR)
    {

    parent::__construct($color);

    
        if (count($points) < 3){
            throw new \InvalidArgumentException
            ("un polygone doit avoir au moins 3 sommets");
        }
        foreach ($points as $point) {
            if (!$point instanceof Point) {
                throw new \InvalidArgumentException ("un polygone ne peut contenir que des points");

            }
           
        }
         $this->points = $points;
    }

    public function pointCount(): int
    {
        return count($this->points);
    }

    /**
     * TODO : la formule du lacet (shoelace).
     *
     * On parcourt les sommets deux par deux, en bouclant du dernier au premier
     * (`($i + 1) % $count`), on additionne `x_i × y_suivant - x_suivant × y_i`,
     * et l'aire vaut la valeur absolue de la somme divisée par 2.
     */
    public function area(): float
    {
        $count = $this->pointCount();
        $sum = 0.0;

        for ($i = 0; $i < $count; $i++) {
            $next = ($i + 1) % $count;
            $sum += $this->points[$i]->x * $this->points[$next]->y
                - $this->points[$next]->x * $this->points[$i]->y;
        }

        return abs($sum) / 2;
    }
}

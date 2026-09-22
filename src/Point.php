<?php

declare(strict_types=1);

namespace Shapes;

/**
 * ÉTAPE 1 — Un point du plan : deux coordonnées, et rien d'autre.
 *
 * Un point est une valeur, pas un objet qui vit sa vie : une fois construit,
 * il ne change plus. Rendez la classe `readonly` et déclarez `x` et `y`
 * en promotion de constructeur (`public float $x`). Pas de `x()` ni de `y()` :
 * une propriété `readonly` se lit directement, `$point->x`.
 *
 * Indice : `final readonly class Point implements \Stringable`.
 */
final readonly class Point implements \Stringable
{
    // TODO : le constructeur. Deux paramètres promus, `public float $x` et
    // `public float $y`. Rien d'autre à écrire dans le corps.
    public function __construct(
        public float $x, 
        public float $y)
    {
    }
    /**
     * TODO : rendre un NOUVEAU point décalé de $dx et $dy.
     * Attention : l'objet courant ne doit pas bouger.
     */
    public function translate(float $dx, float $dy): self
    {
       return new self($this->x + $dx, $this->y + $dy);
    }
    
    /** TODO : la distance euclidienne. Racine de (dx² + dy²). */
    public function distanceTo(self $other): float
    {
        $dx = $this->x - $other->x;
        $dy = $this->y - $other->y;
        return sqrt($dx * $dx + $dy * $dy);
    }

    /** TODO : deux points sont égaux s'ils ont les mêmes coordonnées. */
    public function equals(self $other): bool
    {
        return $this->x === $other->x && $this->y === $other->y;
    }

    /** TODO : rendre « (10, -3) ». */
    public function __toString(): string
    {
           return '(' . $this->x . ', ' . $this->y . ')';
    }
}

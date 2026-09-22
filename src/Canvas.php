<?php

declare(strict_types=1);

namespace Shapes;

/**
 * ÉTAPE 3 — La feuille de dessin. Une taille, un fond, et une liste de formes.
 *
 * Constructeur attendu :
 * `new Canvas(float $width, float $height, string $background = '#FFFFFF')`.
 * Doit exposer `public readonly float $width`, `public readonly float $height`
 * et `public readonly string $background` (normalisé en majuscules).
 * Taille nulle ou négative, ou fond invalide : `\InvalidArgumentException`.
 *
 * La liste des formes est `public private(set)` : tout le monde la lit
 * (`$canvas->shapes`), seul `add()` l'écrit.
 *
 * Remarquez : `Canvas` n'hérite PAS de `Shape`. Un canvas n'est pas une forme,
 * il en contient. C'est de la composition, pas de l'héritage.
 */
final class Canvas
{
    /** @var list<Shape> */
    public private(set) array $shapes = [];

    /** TODO : `public readonly string $background;` déclarée ici, remplie par le constructeur. */
   public readonly string $background;
    // TODO : promouvoir `$width` et `$height` en `public readonly`, valider,
    // puis `$this->background = strtoupper($background);`.
    public function __construct(
        public readonly float $width,
        public readonly float $height, 
        string $background = '#FFFFFF')
    {
       
          if ($width <= 0) {
            throw new \InvalidArgumentException("longueur invalide : {$width}");
        }
        if ($height <= 0) {
            throw new \InvalidArgumentException("hauteur invalide : {$height}");
        }

          if (preg_match('/^#[0-9A-Fa-f]{6}$/', $background) !== 1) {
            throw new \InvalidArgumentException("Couleur invalide : {$background}");
 }
        $this->background = strtoupper($background); 
          
   
    }
    /**
     * TODO : ajouter la forme à la liste.
     *
     * Le type du paramètre suffit à refuser un `Point` : PHP lève un `TypeError`
     * tout seul, vous n'avez aucun `if` à écrire.
     */
    public function add(Shape $shape): void
    {
        $this->shapes [] = $shape;
    }

    public function isEmpty(): bool
    {
        return count($this->shapes) ===0;
    }

    /**
     * TODO : la somme des aires de toutes les formes.
     *
     * C'est le polymorphisme : vous appelez `area()` sans jamais demander
     * « et toi, tu es quoi ? ». Aucun `if`, aucun `instanceof`.
     */
    public function totalArea(): float
    {
        $total = 0.0;

        foreach ($this->shapes as $shape) {
            $total += $shape->area ();
        }
     return $total;
        
    }
}

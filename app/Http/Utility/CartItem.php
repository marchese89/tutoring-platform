<?php

namespace App\Http\Utility;

use App\Models\Lesson;
use App\Models\Exercise;
use App\Models\LessonOnRequest;
use App\Models\Course;

class CartItem
{
    public const LEZIONE = 0;
    public const LEZIONI_CORSO = 1;
    public const ESERCIZIO = 2;
    public const ESERCIZI_CORSO = 3;
    public const CORSO_COMPLETO = 4;
    public const LEZIONE_RICHIESTA = 5;

    private int $id;
    private int $tipo;
    private int $prezzo = 0;
    private string $nome;

    public function __construct(int $id, int $tipo)
    {
        $this->id = $id;
        $this->tipo = $tipo;

        $this->init();
    }

    private function init(): void
    {
        switch ($this->tipo) {

            case self::LEZIONE:
                $l = Lesson::findOrFail($this->id);
                $this->nome = $l->title;
                $this->prezzo = $l->price;
                break;

            case self::ESERCIZIO:
                $e = Exercise::findOrFail($this->id);
                $this->nome = $e->title;
                $this->prezzo = $e->price;
                break;

            case self::LEZIONE_RICHIESTA:
                $r = LessonOnRequest::findOrFail($this->id);
                $this->nome = "Lezione su richiesta: " . $r->title;
                $this->prezzo = $r->price;
                break;

            case self::LEZIONI_CORSO:
                $c = Course::findOrFail($this->id);
                $this->nome = "Tutte le lezioni: " . $c->name;
                break;

            case self::ESERCIZI_CORSO:
                $c = Course::findOrFail($this->id);
                $this->nome = "Tutti gli esercizi: " . $c->name;
                break;

            case self::CORSO_COMPLETO:
                $c = Course::findOrFail($this->id);
                $this->nome = "Corso completo: " . $c->name;
                break;

            default:
                throw new \Exception("Tipo non valido");
        }
    }

    public function getId(): int
    {
        return $this->id;
    }
    public function getTipo(): int
    {
        return $this->tipo;
    }
    public function getPrezzo(): int
    {
        return $this->prezzo;
    }
    public function getNome(): string
    {
        return $this->nome;
    }
}

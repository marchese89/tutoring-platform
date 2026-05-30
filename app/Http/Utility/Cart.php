<?php

namespace App\Http\Utility;

use App\Models\Lesson;
use App\Models\Exercise;

class Cart
{
    /** @var CartItem[] */
    private array $items = [];

    public function aggiungi(CartItem $item): bool
    {
        if ($this->exists($item)) {
            return true;
        }

        if ($this->isCovered($item)) {
            return true;
        }

        $this->removeCovered($item);

        $this->items[] = $item;

        return true;
    }

    private function exists(CartItem $item): bool
    {
        foreach ($this->items as $i) {
            if ($i->getId() === $item->getId() && $i->getTipo() === $item->getTipo()) {
                return true;
            }
        }
        return false;
    }

    private function isCovered(CartItem $item): bool
    {
        if ($item->getTipo() === CartItem::LEZIONE) {
            $courseId = Lesson::find($item->getId())?->course_id;

            return $this->has($courseId, CartItem::LEZIONI_CORSO)
                || $this->has($courseId, CartItem::CORSO_COMPLETO);
        }

        if ($item->getTipo() === CartItem::ESERCIZIO) {
            $courseId = Exercise::find($item->getId())?->course_id;

            return $this->has($courseId, CartItem::ESERCIZI_CORSO)
                || $this->has($courseId, CartItem::CORSO_COMPLETO);
        }

        return false;
    }

    private function removeCovered(CartItem $item): void
    {
        $id = $item->getId();
        $tipo = $item->getTipo();

        if ($tipo === CartItem::LEZIONI_CORSO) {
            $lessonIds = Lesson::where('course_id', $id)->pluck('id');
            foreach ($lessonIds as $l) {
                $this->rimuovi($l, CartItem::LEZIONE);
            }
        }

        if ($tipo === CartItem::ESERCIZI_CORSO) {
            $exIds = Exercise::where('course_id', $id)->pluck('id');
            foreach ($exIds as $e) {
                $this->rimuovi($e, CartItem::ESERCIZIO);
            }
        }

        if ($tipo === CartItem::CORSO_COMPLETO) {
            $this->rimuovi($id, CartItem::LEZIONI_CORSO);
            $this->rimuovi($id, CartItem::ESERCIZI_CORSO);
        }
    }

    private function has(?int $id, int $tipo): bool
    {
        if (!$id) return false;

        foreach ($this->items as $i) {
            if ($i->getId() === $id && $i->getTipo() === $tipo) {
                return true;
            }
        }
        return false;
    }

    public function rimuovi(int $id, int $tipo): bool
    {
        foreach ($this->items as $k => $i) {
            if ($i->getId() === $id && $i->getTipo() === $tipo) {
                unset($this->items[$k]);
                $this->items = array_values($this->items);
                return true;
            }
        }
        return false;
    }

    public function contenuto(): array
    {
        return $this->items;
    }
    public function nElementi(): int
    {
        return count($this->items);
    }

    public function getTotale(): int
    {
        return array_sum(array_map(fn($i) => $i->getPrezzo(), $this->items));
    }

    public function vuotaCart(): void
    {
        $this->items = [];
    }
}

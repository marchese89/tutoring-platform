<?php

namespace App\Http\Utility;

use App\Models\Lesson;
use App\Models\Exercise;

class Cart
{
    /** @var CartItem[] */
    private array $items = [];

    public function add(CartItem $item): bool
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
        foreach ($this->items as $existingItem) {
            if ($existingItem->id() === $item->id() && $existingItem->type() === $item->type()) {
                return true;
            }
        }
        return false;
    }

    private function isCovered(CartItem $item): bool
    {
        if ($item->type() === CartItem::LESSON) {
            $courseId = Lesson::find($item->id())?->course_id;

            return $this->has($courseId, CartItem::COURSE_LESSONS)
                || $this->has($courseId, CartItem::FULL_COURSE);
        }

        if ($item->type() === CartItem::EXERCISE) {
            $courseId = Exercise::find($item->id())?->course_id;

            return $this->has($courseId, CartItem::COURSE_EXERCISES)
                || $this->has($courseId, CartItem::FULL_COURSE);
        }

        return false;
    }

    private function removeCovered(CartItem $item): void
    {
        $id = $item->id();
        $type = $item->type();

        if ($type === CartItem::COURSE_LESSONS) {
            $lessonIds = Lesson::where('course_id', $id)->pluck('id');
            foreach ($lessonIds as $lessonId) {
                $this->remove($lessonId, CartItem::LESSON);
            }
        }

        if ($type === CartItem::COURSE_EXERCISES) {
            $exerciseIds = Exercise::where('course_id', $id)->pluck('id');
            foreach ($exerciseIds as $exerciseId) {
                $this->remove($exerciseId, CartItem::EXERCISE);
            }
        }

        if ($type === CartItem::FULL_COURSE) {
            $this->remove($id, CartItem::COURSE_LESSONS);
            $this->remove($id, CartItem::COURSE_EXERCISES);
        }
    }

    private function has(?int $id, int $type): bool
    {
        if (!$id) {
            return false;
        }

        foreach ($this->items as $item) {
            if ($item->id() === $id && $item->type() === $type) {
                return true;
            }
        }
        return false;
    }

    public function remove(int $id, int $type): bool
    {
        foreach ($this->items as $index => $item) {
            if ($item->id() === $id && $item->type() === $type) {
                unset($this->items[$index]);
                $this->items = array_values($this->items);
                return true;
            }
        }
        return false;
    }

    /**
     * @return CartItem[]
     */
    public function items(): array
    {
        return $this->items;
    }

    public function count(): int
    {
        return count($this->items);
    }

    public function total(): int
    {
        return array_sum(array_map(fn(CartItem $item) => $item->price(), $this->items));
    }

    public function clear(): void
    {
        $this->items = [];
    }
}

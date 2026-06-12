<?php

namespace App\Http\Utility;

use App\Enums\ProductType;
use App\Models\Course;
use App\Models\Exercise;
use App\Models\Lesson;
use App\Models\LessonRequest;

class CartItem
{
    public const LESSON = ProductType::LESSON->value;

    public const COURSE_LESSONS = ProductType::COURSE_LESSONS->value;

    public const EXERCISE = ProductType::EXERCISE->value;

    public const COURSE_EXERCISES = ProductType::COURSE_EXERCISES->value;

    public const FULL_COURSE = ProductType::FULL_COURSE->value;

    public const REQUESTED_LESSON = ProductType::REQUESTED_LESSON->value;

    private int $id;

    private int $type;

    private int $price = 0;

    private string $name;

    public function __construct(int $id, int $type)
    {
        $this->id = $id;
        $this->type = $type;

        $this->init();
    }

    private function init(): void
    {
        switch ($this->type) {

            case self::LESSON:
                $lesson = Lesson::findOrFail($this->id);
                $this->name = $lesson->title;
                $this->price = $lesson->price;
                break;

            case self::EXERCISE:
                $exercise = Exercise::findOrFail($this->id);
                $this->name = $exercise->title;
                $this->price = $exercise->price;
                break;

            case self::REQUESTED_LESSON:
                $request = LessonRequest::findOrFail($this->id);
                $this->name = 'Requested lesson: '.$request->title;
                $this->price = $request->price;
                break;

            case self::COURSE_LESSONS:
                $course = Course::findOrFail($this->id);
                $this->name = 'All lessons: '.$course->name;
                break;

            case self::COURSE_EXERCISES:
                $course = Course::findOrFail($this->id);
                $this->name = 'All exercises: '.$course->name;
                break;

            case self::FULL_COURSE:
                $course = Course::findOrFail($this->id);
                $this->name = 'Full course: '.$course->name;
                break;

            default:
                throw new \Exception('Invalid cart item type');
        }
    }

    public function id(): int
    {
        return $this->id;
    }

    public function type(): int
    {
        return $this->type;
    }

    public function price(): int
    {
        return $this->price;
    }

    public function name(): string
    {
        return $this->name;
    }
}

<?php

namespace App\Enums;

enum ProductType: int
{
    case LESSON = 0;
    case COURSE_LESSONS = 1;
    case EXERCISE = 2;
    case COURSE_EXERCISES = 3;
    case FULL_COURSE = 4;
    case REQUESTED_LESSON = 5;
}

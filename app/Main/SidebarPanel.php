<?php

namespace App\Main;

class SidebarPanel
{
    public static function dashboard()
    {
        return [
            'title' => 'Dashboard',
            'items' => [
                [
                    'dashboard' => [
                        'title' => 'Today & Overview',
                        'route_name' => 'index',
                    ],
                ],
            ],
        ];
    }

    public static function workouts()
    {
        return [
            'title' => 'Workouts',
            'items' => [
                [
                    'workout_index' => [
                        'title' => 'All Workouts',
                        'route_name' => 'workout.index',
                    ],
                    'workout_create' => [
                        'title' => 'Create Workout Plan',
                        'route_name' => 'workout.create',
                    ],
                ],
            ],
        ];
    }

    public static function meals()
    {
        return [
            'title' => 'Meals & Nutrition',
            'items' => [
                [
                    'meal_index' => [
                        'title' => 'All Meal Plans',
                        'route_name' => 'meal.index',
                    ],
                    'meal_create' => [
                        'title' => 'Create Meal Plan',
                        'route_name' => 'meal.create',
                    ],
                ],
            ],
        ];
    }

    public static function exercises()
    {
        return [
            'title' => 'Exercises',
            'items' => [
                [
                    'exercise_index' => [
                        'title' => 'Exercise Library',
                        'route_name' => 'exercise.index',
                    ],
                    'muscle_groups' => [
                        'title' => 'Anatomical Muscle Groups',
                        'route_name' => 'muscle-groups.index',
                    ],
                    'exercise_create' => [
                        'title' => 'Add New Exercise',
                        'route_name' => 'exercise.create',
                    ],
                ],
            ],
        ];
    }

    public static function body()
    {
        return [
            'title' => 'Body Sections',
            'items' => [
                [
                    'muscle_map' => [
                        'title' => 'Interactive Muscle Map',
                        'route_name' => 'muscle-groups.index',
                    ],
                    'body_index' => [
                        'title' => 'All Muscle Groups',
                        'route_name' => 'body.index',
                    ],
                    'body_create' => [
                        'title' => 'Add Body Section',
                        'route_name' => 'body.create',
                    ],
                ],
            ],
        ];
    }

    public static function vitals()
    {
        return [
            'title' => 'Vitals Tracking',
            'items' => [
                [
                    'vital_index' => [
                        'title' => 'Vitals Overview',
                        'route_name' => 'vital.index',
                    ],
                    'vital_create' => [
                        'title' => 'Record Vital',
                        'route_name' => 'vital.create',
                    ],
                ],
            ],
        ];
    }

    public static function all()
    {
        return [
            self::dashboard(),
            self::workouts(),
            self::meals(),
            self::exercises(),
            self::body(),
            self::vitals(),
        ];
    }
}

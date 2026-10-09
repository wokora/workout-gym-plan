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
            'title' => 'Workouts & Training',
            'items' => [
                [
                    'workout_index' => [
                        'title' => 'All Workouts',
                        'route_name' => 'workout.index',
                    ],
                    'exercise_index' => [
                        'title' => 'Exercise Library',
                        'route_name' => 'exercise.index',
                    ],
                    'muscle_map' => [
                        'title' => 'Interactive Muscle Map',
                        'route_name' => 'muscle-groups.index',
                    ],
                    'body_index' => [
                        'title' => 'Muscle Groups',
                        'route_name' => 'body.index',
                    ],
                ],
            ],
        ];
    }

    public static function exercises()
    {
        return self::workouts();
    }

    public static function body()
    {
        return self::workouts();
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
                    'food_index' => [
                        'title' => 'Food Database',
                        'route_name' => 'food.index',
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
                ],
            ],
        ];
    }

    public static function diseases()
    {
        return [
            'title' => 'Diseases & Cures',
            'items' => [
                [
                    'disease_index' => [
                        'title' => 'Diseases Database',
                        'route_name' => 'disease.index',
                    ],
                    'treatment_index' => [
                        'title' => 'Treatments & Cures',
                        'route_name' => 'treatment.index',
                    ],
                    'symptom_index' => [
                        'title' => 'Symptom Directory',
                        'route_name' => 'symptom.index',
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
            self::vitals(),
            self::diseases(),
        ];
    }
}

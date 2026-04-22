<?php

enum MealType: int
{
    case Breakfast = 0;
    case Lunch = 1;
    case Dinner = 2;
    case Snack = 3;

    public static function getMealTypeFromString(string $mealType):MealType{
        if(strtolower($mealType) == "breakfast"){
            return MealType::Breakfast;
        }else if(strtolower($mealType) == "lunch"){
            return MealType::Lunch;
        }else if(strtolower($mealType)=="dinner"){
            return MealType::Dinner;
        }else{
            return MealType::Snack;
        }
    }
}

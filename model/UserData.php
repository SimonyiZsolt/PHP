<?php

class UserData implements JsonSerializable
{

    private int $goal;
    private int $age;
    private int $height;
    private int $weight;
    private int $gender;
    private float $activity;
    private int $rmr;
    private int $bmr;
    private int $calorieTarget;

    public function __construct(int $goal, int $age, int $height, int $weight, int $gender, float $activity, int|null $rmr = null, int|null $bmr = null, int|null $calorieTarget = null)
    {
        $this->setGoal($goal);
        $this->setAge($age);
        $this->setHeight($height);
        $this->setWeight($weight);
        $this->setGender($gender);
        $this->setActivity($activity);

        if ($rmr == null || $bmr == null || $calorieTarget == null) {
            $this->calculateAndSetRMR();
            $this->calculateAndSetBMR();
            $this->calculateAndSetCalorieTarget();
        } else {
            $this->rmr = $rmr;
            $this->bmr = $bmr;
            $this->calorieTarget = $calorieTarget;
        }
    }

    private function setGoal(int $goal): void
    {
        $this->goal = $goal;
    }

    private function setAge(int $age): void
    {
        $this->age = $age;
    }

    private function setHeight(int $height): void
    {
        $this->height = $height;
    }

    private function setWeight(int $weight): void
    {
        $this->weight = $weight;
    }

    private function setGender(int $gender): void
    {
        $this->gender = $gender;
    }

    private function setActivity(float $activity): void
    {
        $this->activity = $activity;
    }

    private function calculateAndSetRMR(): void
    {

        if ($this->gender == 0) {/*Female*/
            $this->rmr = ceil((10 * $this->weight) + (6.25 * $this->height) - (5 * $this->age) - 161);
        } else {
            $this->rmr = ceil((10 * $this->weight) + (6.25 * $this->height) - (5 * $this->age) + 5);
        }
    }

    private function calculateAndSetBMR(): void
    {

        $this->bmr = ceil($this->rmr * $this->activity);

    }

    private function calculateAndSetCalorieTarget(): void
    {
        if ($this->goal == 0) {
            
            $this->calorieTarget = $this->bmr - 500;
        } else if ($this->goal == 1) {
            $this->calorieTarget = $this->bmr;
        } else {
            $this->calorieTarget = $this->bmr + 500;
        }
    }

    public function getGoal(): int
    {
        return $this->goal;
    }

    public function getAge(): int
    {
        return $this->age;
    }

    public function getHeight(): int
    {
        return $this->height;
    }

    public function getWeight(): int
    {
        return $this->weight;
    }

    public function getGender(): int
    {
        return $this->gender;
    }

    public function getActivity(): float
    {
        return $this->activity;
    }

    public function getRMR(): int
    {
        return $this->rmr;
    }

    public function getBMR(): int
    {
        return $this->bmr;
    }

    public function getCalorieTarget(): int
    {
        return $this->calorieTarget;
    }

    public function jsonSerialize(): mixed
    {
        return get_object_vars($this);
    }
}

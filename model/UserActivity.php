<?php
include_once("Activity.php");
class UserActivity implements JsonSerializable
{
    private int $userActivityId;
    private Activity $activity;
    private int $durationMinutes;

    public function __construct(int $userActivityId, Activity $activity, int $durationMinutes)
    {
        $this->setId($userActivityId);
        $this->setActivity($activity);
        $this->setDurationMinutes($durationMinutes);
    }

    private function setId($id)
    {
        $this->userActivityId = $id;
    }

    private function setActivity(Activity $activity)
    {
        $this->activity = $activity;
    }

    private function setDurationMinutes(int $minutes)
    {
        $this->durationMinutes = $minutes;
    }

    public function getUserActivityId(): int
    {
        return $this->userActivityId;
    }

    public function getActivity(): Activity
    {
        return $this->activity;
    }

    public function getDurationMinutes(): int
    {
        return $this->durationMinutes;
    }

    public function jsonSerialize(): mixed
    {
        return get_object_vars($this);
    }
}

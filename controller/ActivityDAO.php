<?php
if (str_contains(getcwd(), "api")) {
    include_once("../model/Activity.php");
    include_once("../model/UserActivity.php");
} else {
    include_once("model/Activity.php");
    include_once("model/UserActivity.php");
}


class ActivityDAO
{
    private static string $servername = "localhost";
    private static string $username = "root";
    private static string $password = "";
    private static string $dbname = "food_db";

    private static function getDBConnection(): mysqli
    {
        $conn = new mysqli(self::$servername, self::$username, self::$password, self::$dbname);
        
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        return $conn;
    }

    public static function getActivityList(): array
    {
        $conn = self::getDBConnection();
        $stmt = $conn->prepare("SELECT * FROM activities ORDER BY category DESC, name");
        $stmt->execute();
        $stmt->bind_result($id, $name, $met, $category);

        $arr = [];
        while ($stmt->fetch()) {
            $activity = new Activity(intval($id), strval($name), floatval($met), strval($category));
            $arr[] = $activity;
        }

        $stmt->close();
        $conn->close();
        return $arr;
    }

    public static function addActivityForUserAndDate(int $activityId, string $userName, int $durationMinutes, string $date)
    {
        $conn = self::getDBConnection();
        $stmt = $conn->prepare("INSERT INTO user_activities (username, activity_id, duration_minutes, date) VALUES (?,?,?,?)");

        $stmt->bind_param("siis", $userName, $activityId, $durationMinutes, $date);
        $stmt->execute();
        $insertId = $stmt->insert_id;

        $stmt->close();
        $conn->close();
        return $insertId;
    }

    public static function getActivitiesForUserAndDate(string $userName, string $date)
    {
        $conn = self::getDBConnection();
        $stmt = $conn->prepare("SELECT activities.id, name, met, user_activities.id, duration_minutes FROM activities JOIN user_activities ON activities.id = user_activities.activity_id WHERE username = ? AND date = ?;");
        $stmt->bind_param("ss", $userName, $date);
        $stmt->execute();

        $stmt->bind_result($activityId, $name, $met, $userActivityId, $durationMinutes);
        $arr = [];
        while ($stmt->fetch()) {
            $activity = new Activity(intval($activityId), strval($name), floatval($met));
            $userActivity = new UserActivity(intval($userActivityId), $activity, intval($durationMinutes));
            $arr[] = $userActivity;
        }

        $stmt->close();
        $conn->close();

        return $arr;
    }

    public static function updateUserActivity(int $userActivityId, int $newDuration)
    {
        $conn = self::getDBConnection();
        $stmt = $conn->prepare("UPDATE user_activities SET duration_minutes = ? WHERE id = ?");
        $stmt->bind_param("ii", $newDuration, $userActivityId);
        $stmt->execute();

        $stmt->close();
        $conn->close();
    }

    public static function deleteUserActivityByID(int $userActivityId)
    {
        $conn = self::getDBConnection();
        $stmt = $conn->prepare("DELETE FROM user_activities WHERE id = ?");
        $stmt->bind_param("i", $userActivityId);
        $stmt->execute();

        $stmt->close();
        $conn->close();
    }
}

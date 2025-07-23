<?php
class SkillPost {
    private $conn;
    private $title;
    private $category;
    private $description;
    private $level;

    public function __construct($conn, $title, $category, $description, $level) {
        $this->conn = $conn;
        $this->title = $this->sanitize($title);
        $this->category = $this->sanitize($category);
        $this->description = $this->sanitize($description);
        $this->level = $this->sanitize($level);
    }

    private function sanitize($value) {
        return mysqli_real_escape_string($this->conn, $value);
    }

    private function getSkillId() {
        $query = "SELECT skill_id FROM skill WHERE LOWER(skill_name) = LOWER('$this->category') LIMIT 1";
        $result = mysqli_query($this->conn, $query);
        if ($result && mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            return $row['skill_id'];
        }
        return null;
    }

    public function save() {
        $skill_id = $this->getSkillId();
        if ($skill_id === null) {
            return ["success" => false, "error" => "No matching skill_id found for the selected category."];
        }

        $query = "INSERT INTO post_skills (skill_title, category, skill_id, description, level)
                  VALUES ('$this->title', '$this->category', '$skill_id', '$this->description', '$this->level')";

        if (mysqli_query($this->conn, $query)) {
            return ["success" => true];
        } else {
            return ["success" => false, "error" => mysqli_error($this->conn)];
        }
    }
}
?>

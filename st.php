<?php

class Student{

    private $conn;
    private $st_table = "students";
    private $grade_table = "grades";

    public $id;
    public $name;
    public $st_id;
    public $st_grade;

    public function __construct($db){
        $this->conn = $db;
    }

    function getGrades($id){

        $query = "SELECT st_grade
				FROM " . $this->grade_table . "
				WHERE st_id = " . $id;

        $statement  = $this->conn->prepare( $query );
        $statement->execute();
        $grade_num = $statement ->rowCount();

        if($grade_num > 0 && $grade_num < 5){

            $average_query = "SELECT AVG(st_grade) as csm_grade
				FROM " . $this->grade_table . "
				WHERE st_id = " . $id;
            $average_statement  = $this->conn->prepare( $average_query );
            $average_statement ->execute();

            $row = $average_statement ->fetch(PDO::FETCH_ASSOC);

            $average_grade = $row['csm_grade'];

            if ($average_grade >= 7) {
                $grade_result = "Pass";
            }
            else {
                $grade_result = "Fail";
            }

        }

        $student_query = "SELECT id, name
				FROM " . $this->st_table . "
				WHERE id = " . $id . "
				LIMIT 0,1";

        $student_statement  = $this->conn->prepare( $student_query );
        $student_statement ->execute();

        $student_num = $student_statement ->rowCount();

        if($student_num>0){

            $row = $student_statement ->fetch(PDO::FETCH_ASSOC);

            $st_id = $row['id'];
            $st_name = $row['name'];

        }

        $csm_result = array(
            "st_id" => $st_id,
            "st_name" => $st_name,
            "exam_result" => $grade_result,
            "grades" => $statement->fetchAll(PDO::FETCH_ASSOC)

        );

        echo json_encode($csm_result, JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);

    }

}

class xmlResponse{

    static function parse($arr){
        $dom = new DOMDocument('1.0');
        self::recursiveParser($dom,$arr,$dom);
        return $dom->saveXML();
    }

    private static function recursiveParser(&$root, $arr, &$dom){
        foreach($arr as $key => $item){
            if(is_array($item) && !is_numeric($key)){
                $node = $dom->createElement($key);
                self::recursiveParser($node,$item,$dom);
                $root->appendChild($node);
            }
            elseif(is_array($item) && is_numeric($key)){
                self::recursiveParser($root,$item,$dom);
            }
            else{
                $node = $dom->createElement($key, $item);
                $root->appendChild($node);
            }
        }
    }

}

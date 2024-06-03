<?php
session_start();


class DatabaseHandler {

    private $pdo;

    public function __construct() {
        // Set your database connection parameters here
        $dbHost = 'localhost';
        $dbPort = '3306';
        $dbName = 'db_schedulemate';
        $dbUser = 'root';
        $dbPassword = '';

        try {
            $dsn = "mysql:host=$dbHost;port=$dbPort;dbname=$dbName";
            $this->pdo = new PDO($dsn, $dbUser, $dbPassword);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            // Handle connection errors
            echo "Connection failed: " . $e->getMessage();
        }
    }

    public function getAllRowsFromTable($tableName) {
        try {
            $stmt = $this->pdo->prepare("SELECT * FROM $tableName WHERE status = 0");
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $result;
        } catch (PDOException $e) {
            // Handle query errors
            echo "Query failed: " . $e->getMessage();
        }
    }

    public function getAllRowsFromTableLimitBy($tableName,$count) {
        try {
            $stmt = $this->pdo->prepare("SELECT * FROM $tableName 
            WHERE status = 0 AND process_status != 'done' ORDER BY id desc LIMIT $count");
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $result;
        } catch (PDOException $e) {
            // Handle query errors
            echo "Query failed: " . $e->getMessage();
        }
    }
    
    
    public function getAllRowsFromTableWhere($tableName, array $additionalConditions = []) {
        try {
            // Construct the WHERE clause with status = 0 and additional conditions
            $whereClause = "status != 123123123";
    
            if (!empty($additionalConditions)) {
                $whereClause .= " AND " . implode(' AND ', $additionalConditions);
            }
    
            // Prepare the SQL statement with the dynamic WHERE clause
            $sql = "SELECT * FROM $tableName WHERE $whereClause";
            $stmt = $this->pdo->prepare($sql);
    
            // Execute the query
            $stmt->execute();
    
            // Fetch the results as an associative array
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
            return $result;
        } catch (PDOException $e) {
            // Handle query errors
            echo "Query failed: " . $e->getMessage();
        }
    }

    // Get all data of the schedules with the additional conditions
    public function getAllDataCourse($tableName, array $additionalConditions = []) {
        try {
            // Construct the WHERE clause with status = 0 and additional conditions
            $whereClause = "$tableName.status = 1";
    
            if (!empty($additionalConditions)) {
                $whereClause .= " AND " . implode(' AND ', $additionalConditions);
            }
    
            // Prepare the SQL statement with the dynamic WHERE clause
            $sql = "SELECT * FROM $tableName 
            LEFT JOIN tb_section AS sec ON $tableName.section_id = sec.secID 
            LEFT JOIN tb_room AS room ON $tableName.room_id = room.roomID 
            LEFT JOIN tb_subjects AS subject ON $tableName.subject_id = subject.subID
            LEFT JOIN tb_professor AS professor ON $tableName.prof_id = professor.profID
            WHERE $whereClause";
            $stmt = $this->pdo->prepare($sql);
    
            // Execute the query
            $stmt->execute();
    
            // Fetch the results as an associative array
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return $result;
        } catch (PDOException $e) {
            // Handle query errors
            echo "Query failed: " . $e->getMessage();
        }
    }

    
    public function getAllRowsFromTableWhere2($tableName,$whereClause, array $additionalConditions = []) {
        try {
            // Construct the WHERE clause with status = 0 and additional conditions
            if($whereClause!=""){
                if (!empty($additionalConditions)) {
                    $whereClause .= " AND " . implode(' AND ', $additionalConditions);
                }
            }
    
    
            // Prepare the SQL statement with the dynamic WHERE clause
            $sql = "SELECT * FROM $tableName WHERE $whereClause";
            $stmt = $this->pdo->prepare($sql);
    
            // Execute the query
            $stmt->execute();
    
            // Fetch the results as an associative array
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
            return $result;
        } catch (PDOException $e) {
            // Handle query errors
            echo "Query failed: " . $e->getMessage();
        }
    }
    public function getAllRowsFromTableWhereGroup($tableName, array $additionalConditions = [], $groupBy = null) {
        try {
            // Construct the WHERE clause with status = 0 and additional conditions
            $whereClause = "status = 0";
    
            if (!empty($additionalConditions)) {
                $whereClause .= " AND " . implode(' AND ', $additionalConditions);
            }
    
            // Construct the GROUP BY clause if $groupBy is provided
            $groupByClause = "";
            if (!empty($groupBy)) {
                $groupByClause = " GROUP BY " . $groupBy;
            }
    
            // Prepare the SQL statement with the dynamic WHERE and GROUP BY clauses
            $sql = "SELECT * FROM $tableName WHERE $whereClause $groupByClause";
            $stmt = $this->pdo->prepare($sql);
    
            // Execute the query
            $stmt->execute();
    
            // Fetch the results as an associative array
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
            return $result;
        } catch (PDOException $e) {
            // Handle query errors
            echo "Query failed: " . $e->getMessage();
        }
    }

    // Display for the schedules in the manage schedule
    public function getAllRowsFromTableWhereGroup2($tableName, $additionalConditions = array(), $groupBy = null) {
        try {
            // Construct the WHERE clause with status = 1 and additional conditions
            $whereClause = "$tableName.status = 1";

            if (!empty($additionalConditions)) {
                $whereClause .= " AND " . implode(' AND ', $additionalConditions);
            }
    
            // Construct the GROUP BY clause if $groupBy is provided
            $groupByClause = !empty($groupBy) ? " GROUP BY $groupBy" : "";
    
            // Prepare the SQL statement with the dynamic WHERE and GROUP BY clauses
            $sql = "SELECT * FROM $tableName LEFT JOIN tb_section as sec ON $tableName.section_id = sec.secID WHERE $whereClause $groupByClause";
            $stmt = $this->pdo->prepare($sql);
    
            // Execute the query
            $stmt->execute();

            // Fetch the results as an associative array
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
            return $result;
        } catch (PDOException $e) {
            // Handle query errors
            echo "Query failed: " . $e->getMessage();
        }
    }
    
    
    public function loginUser($username, $password) {
        try {
            $username = htmlentities($username);
            $password = htmlentities($password);
            $stmt = $this->pdo->prepare("SELECT * FROM user WHERE email = :email AND status = 0");
            $stmt->bindParam(':email', $username, PDO::PARAM_STR);
            $stmt->execute();
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user) {
                // Here, you should compare the password using a secure method like password_verify
                if ($user['password'] === $password) {
                    $_SESSION['user'] = $user['position'];
                    $_SESSION['id'] = $user['id'];
                    $_SESSION['name'] = $user['name'];
                    return true; // Login successful
                }
            }

            return false; // Login failed

        } catch (PDOException $e) {
            // Handle query errors
            echo "Login query failed: " . $e->getMessage();
            return false;
        }
    }
    

    public function insertData($tableName, $data)
    {
        $checker = $this->checkDataSchedule($data); // fetching data if there is a conflict schedule

        // if there is a conflict schedule true return false;
        if ($checker) {
            return false;
        }
        // if there is no conflict it will insert the schedule
        $columns = implode(', ', array_keys($data));
        $placeholders = ':' . implode(', :', array_keys($data));

        $sql = "INSERT INTO $tableName ($columns) VALUES ($placeholders)";
        $stmt = $this->pdo->prepare($sql);

        foreach ($data as $key => $value) {
            $stmt->bindValue(':' . $key, $value);
        }

        $stmt->execute();
        return true;
    }
    
    public function checkDataSchedule ($data = array()) {
        try {
            $stmt = $this->pdo->prepare("SELECT COUNT(*) as count FROM tb_scheduled_2 WHERE status = 1 AND room_id = :room_id AND school_yr = :school_yr AND semester = :semester AND start_time <= :end_time AND end_time >= :start_time and day = :day");
            $stmt->bindParam(':room_id', $data['room_id'], PDO::PARAM_STR);
            $stmt->bindParam(':semester', $data['semester'], PDO::PARAM_STR);
            $stmt->bindParam(':school_yr', $data['school_yr'], PDO::PARAM_STR);
            $stmt->bindParam(':start_time', $data['start_time'], PDO::PARAM_STR);
            $stmt->bindParam(':end_time', $data['end_time'], PDO::PARAM_STR);
            $stmt->bindParam(':day', $data['day'], PDO::PARAM_STR);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['count'];
        } catch (PDOException $e) {
            // Handle query errors
            echo "Query failed: " . $e->getMessage();
        }
    }
    

    public function updateData($tableName, $data, $whereConditions) {
        try {
            $setClause = '';
            foreach ($data as $key => $value) {
                $setClause .= "$key = :$key, ";
            }
            // Remove the trailing comma and space from the setClause
            $setClause = rtrim($setClause, ', ');
    
            $whereClause = '';
            foreach ($whereConditions as $whereKey => $whereValue) {
                $whereClause .= "$whereKey = :where_$whereKey AND ";
            }

            // Remove the trailing "AND" from the whereClause
            $whereClause = rtrim($whereClause, ' AND ');
    
            $sql = "UPDATE $tableName SET $setClause WHERE $whereClause";
            $stmt = $this->pdo->prepare($sql);
    
            foreach ($data as $key => $value) {
                $stmt->bindValue(':' . $key, $value);
            }
    
            foreach ($whereConditions as $whereKey => $whereValue) {
                $stmt->bindValue(':where_' . $whereKey, $whereValue);
            }
    
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            echo "Error updating data: " . $e->getMessage();
            return false;
            // You can log or handle the error here.
        }
    }

    
    public function getIdByColumnValue($tableName, $columnName, $columnValue, $idColumnName) {
        try {
            $stmt = $this->pdo->prepare("SELECT $idColumnName FROM $tableName WHERE $columnName = :column_value");
            $stmt->bindParam(':column_value', $columnValue);
            $stmt->execute();
            
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($result) {
                return $result[$idColumnName];
            } else {
                return null; // Entry not found
            }
        } catch (PDOException $e) {
            // echo "Error retrieving ID: " . $e->getMessage();
            return null;
        }
    }
    public function getCountByConditions($tableName, $conditions) {
        try {
            $sql = "SELECT COUNT(*) as count FROM $tableName";
    
            if (!empty($conditions)) {
                $sql .= " WHERE ";
                $whereConditions = [];
    
                foreach ($conditions as $column => $value) {
                    $whereConditions[] = "$column = :$column";
                }
    
                $sql .= implode(" AND ", $whereConditions);
            }
    
            $stmt = $this->pdo->prepare($sql);
    
            foreach ($conditions as $column => $value) {
                $stmt->bindParam(":$column", $value);
            }
    
            $stmt->execute();
            $count = $stmt->fetchColumn();
    
            return $count;
        } catch (PDOException $e) {
            // Handle the exception as needed
            return null;
        }
    }
    
    
    public function getAllColumnsByColumnValue($tableName, $columnName, $columnValue) {
        try {
            $stmt = $this->pdo->prepare("SELECT * FROM $tableName WHERE $columnName = :column_value");
            $stmt->bindParam(':column_value', $columnValue);
            $stmt->execute();
            
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($result) {
                return $result;
            } else {
                return null; // Entry not found
            }
        } catch (PDOException $e) {
            // Handle the exception here
            return null;
        }
    }
    public function getAllColumns($tableName) {
        try {
            $stmt = $this->pdo->prepare("SELECT * FROM $tableName WHERE status = 0");
            $stmt->execute();
            
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($result) {
                return $result;
            } else {
                return null; // Entry not found
            }
        } catch (PDOException $e) {
            // Handle the exception here
            return null;
        }
    }

    public function getMajorCountsByYearSem($year, $sem, $course){
        $qry2 = 'SELECT COUNT(*) as major_counts FROM `tb_subjects` WHERE subSem = "'.$sem.'" AND subYearlvl = "'.$year.'" AND SubCourse = "'.$course.'" AND subType="major"';
        $stmt2 = $this->pdo->prepare($qry2);
    
        $stmt2->execute();
        
        $result2 = $stmt2->fetch(PDO::FETCH_ASSOC);
        return $result2['major_counts'];
    }

    public function getMajorCountsOfPlot($sem, $section, $course){
        $qry2 = 'SELECT COUNT(*) as major_counts FROM `tb_scheduled` as tb1 INNER JOIN `tb_subjects` AS tb2 ON tb1.subject = tb2.subCode WHERE tb2.subSem = "'.$sem.'" AND tb1.subject = tb2.subCode AND tb2.subType = "major" AND tb1.course="'.$course.'" AND tb1.section = "'.$section.'"; ';
        $stmt2 = $this->pdo->prepare($qry2);
    
        $stmt2->execute();
        
        $result2 = $stmt2->fetch(PDO::FETCH_ASSOC);
        return $result2['major_counts'];  
    }

    public function getMajorToDisplay($year, $sem, $section){
        $qry = "SELECT tb_scheduled.*, tb_subjects.* 
                FROM tb_scheduled 
                INNER JOIN tb_subjects 
                ON tb_scheduled.id = tb_subjects.subID
                WHERE tb_scheduled.sy = '$year' 
                AND tb_scheduled.semester = '$sem' 
                AND tb_scheduled.section = '$section'";
        $stmt = $this->pdo->prepare($qry);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    // public function restrictPlottedTime ()


    public function pdf_data_subjects($sy,$sem,$course,$yearlevel) {
        try {
            
            // Prepare the SQL statement with the dynamic WHERE clause
            $sql = "SELECT * FROM `tb_subjects` WHERE 
            (`subYear`) = '$sy' 
            AND (`subSem`) = '$sem'
            AND (`SubCourse`) = '$course'
            AND (`subYearlvl`) = '$yearlevel'
            AND status = 0";
            $stmt = $this->pdo->prepare($sql);
    
            // Execute the query
            $stmt->execute();
    
            // Fetch the results as an associative array
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
            return $result;
        } catch (PDOException $e) {
            // Handle query errors
            echo "Query failed: " . $e->getMessage();
        }
    }

}
?>

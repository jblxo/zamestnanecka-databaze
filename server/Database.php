<?php

class Database {
    public $db;
    private $host = 'localhost';
    private $user = 'root';
    private $pass = '';
    private $database = 'employees_management';

    public function __construct() {
        $this->db = mysqli_connect($this->host, $this->user, $this->pass, $this->database);

        if(!$this->db) {
            echo 'Error: Unable to connect to MySQL.'.PHP_EOL;
            echo 'Debugging errno: '.mysqli_connect_errno().PHP_EOL;
            echo 'Debugging error: '.mysqli_connect_error().PHP_EOL;
        }
    }

    public function signUp($firstName, $lastName, $password, $email, $companyName) {
        $sql = $this->db->prepare('INSERT INTO users (firstName, lastName, password, email, companyName) VALUES (?, ?, ?, ?, ?)');
        $sql->bind_param('sssss', $firstName, $lastName, $password, $email, $companyName);
        $sql->execute();
        printf($sql->error);
        $sql = $this->db->prepare('SELECT id FROM users WHERE email = ?');
        $sql->bind_param('s', $email);
        $sql->execute();
        printf($sql->error);
        $result = $sql->get_result();
        $user = $result->fetch_object();
        $sql->close();

        return $user;
    }

    public function signIn($email, $password) {
        $sql = $this->db->prepare('SELECT id, password FROM users WHERE email = ?');
        $sql->bind_param('s', $email);
        $sql->execute();
        printf($sql->error);
        $result = $sql->get_result();
        $user = $result->fetch_object();
        if(!password_verify($password, $user->password)) {
            echo 'Wrong email or password!';
            $sql->close();

            return null;
        }
        $sql->close();
        
        return $user;
    }

    public function addDepartment($name, $abbreviation, $city, $color, $user) {
        $sql = $this->db->prepare('INSERT INTO departments (name, abbreviation, city, color, user) VALUES (?, ?, ?, ?, ?)');
        $sql->bind_param('ssssi', $name, $abbreviation, $city, $color, $user);
        $sql->execute();
        printf($sql->error);
    }

    public function getDepartments($user) {
        $sql = $this->db->prepare('SELECT d.id as id, d.name as name, d.abbreviation, d.city, d.color, COUNT(ed.employee) as employeeCount FROM departments as d LEFT JOIN employees_departments as ed ON d.id = ed.department WHERE user = ? GROUP BY d.id');
        $sql->bind_param('i', $user);
        $sql->execute();
        printf($sql->error);
        $result = $sql->get_result();
        $sql->close();

        return $result;
    }

    public function removeDepartment($department) {
        $sql = $this->db->prepare('DELETE FROM departments WHERE id = ?');
        $sql->bind_param('i', $department);
        $sql->execute();
        printf($sql->error);
    }

    public function getDepartment($department) {
        $sql = $this->db->prepare('
            SELECT d.name as name, d.city as city, d.color as color, d.abbreviation as abbreviation, e.firstName as firstName, e.lastName as lastName, e.createdAt as createdAt FROM departments as d
            LEFT JOIN employees_departments as ed ON ed.department = ?
            LEFT JOIN employees as e ON ed.employee = e.id
            WHERE d.id = ?
            LIMIT 1
        ');
        $sql->bind_param('ii', $department, $department);
        $sql->execute();
        printf($sql->error);
        $result = $sql->get_result();
        $sql->close();
        
        return $result;
    }

    public function getDepartmentWithEmployees($department) {
        $sql = $this->db->prepare('
            SELECT d.name as name, d.city as city, d.color as color, d.abbreviation as abbreviation, e.firstName as firstName, e.lastName as lastName, e.createdAt as createdAt FROM departments as d
            LEFT JOIN employees_departments as ed ON ed.department = ?
            LEFT JOIN employees as e ON ed.employee = e.id
            WHERE d.id = ?
        ');
        $sql->bind_param('ii', $department, $department);
        $sql->execute();
        printf($sql->error);
        $result = $sql->get_result();
        $sql->close();
        
        return $result;
    }

    public function updateDepartment($name, $abbreviation, $city, $color, $department) {
        $sql = $this->db->prepare('UPDATE departments SET name = ?, abbreviation = ?, city = ?, color = ? WHERE id = ?');
        $sql->bind_param('ssssi', $name, $abbreviation, $city, $color, $department);
        $sql->execute();
        printf($sql->error);
    }

    public function getEmployees($user, $department = null) {
        $sql;

        if(is_null($department)) {
            $sql = $this->db->prepare('
                SELECT e.id as id, e.firstName as firstName, e.lastName as lastName FROM employees as e
                LEFT JOIN employees_departments as ed ON e.id = ed.employee
                LEFT JOIN departments as d ON d.id = ed.department
                WHERE e.user = ?
                GROUP BY e.id
            ');
            $sql->bind_param('i', $user);
        } else {
            $sql = $this->db->prepare('
                SELECT e.id as id, e.firstName as firstName, e.lastName as lastName FROM employees as e
                LEFT JOIN employees_departments as ed ON e.id = ed.employee
                LEFT JOIN departments as d ON d.id = ed.department
                WHERE ed.department = ? AND e.user = ?
                GROUP BY e.id
            ');
            $sql->bind_param('ii', $department, $user);
        }

        
        $sql->execute();
        printf($sql->error);
        $result = $sql->get_result();
        $sql->close();

        return $result;
    }

    public function addEmployee($firstName, $lastName, $departments, $user) {
        $sql = $this->db->prepare('INSERT INTO employees (firstName, lastName, user) VALUES (?, ?, ?)');
        $sql->bind_param('ssi', $firstName, $lastName, $user);
        $sql->execute();
        printf($sql->error);
        $employee = $sql->insert_id;
        foreach($departments as $department) {
            $sql = $this->db->prepare('INSERT INTO employees_departments (employee, department) VALUES (?, ?)');
            $sql->bind_param('ii', $employee, $department);
            $sql->execute();
            printf($sql->error);
        }
    }

    public function removeEmployee($employee) {
        $sql = $this->db->prepare('DELETE FROM employees WHERE id = ?');
        $sql->bind_param('i', $employee);
        $sql->execute();
        printf($sql->error);
    }

    public function getEmployee($employee) {
        $sql = $this->db->prepare('
            SELECT e.id as id, e.firstName as firstName, e.lastName as lastName, ed.department as depId, d.name as name, d.color as color FROM employees as e 
            LEFT JOIN employees_departments as ed ON ed.employee = ?
            LEFT JOIN departments as d ON ed.department = d.id 
            WHERE e.id = ?
        ');
        $sql->bind_param('ii', $employee, $employee);
        $sql->execute();
        printf($sql->error);
        $result = $sql->get_result();
        $sql->close();

        return $result;
    }

    public function updateEmployee($employee, $firstName, $lastName, $departments = array()) {
        if($departments == null) {
            $departments = array();
        }
        $sql = $this->db->prepare('UPDATE employees SET firstName = ?, lastName = ? WHERE id = ?');
        $sql->bind_param('ssi', $firstName, $lastName, $employee);
        $sql->execute();
        printf($sql->error);
        $res = $this->getEmployee($employee);
        while($row = $res->fetch_assoc()) {
            if(!in_array($row['depId'], $departments)) {
                $sql = $this->db->prepare('DELETE FROM employees_departments WHERE employee = ? AND department = ?');
                $sql->bind_param('ii', $employee, $row['depId']);
                $sql->execute();
                printf($sql->error);
                if (($key = array_search($row['depId'], $departments)) !== false) {
                    unset($departments[$key]);
                }
            }
        }

        $sql = $this->db->prepare('SELECT department FROM employees_departments as ed WHERE ed.employee = ?');
        $sql->bind_param('i', $employee);
        $sql->execute();
        printf($sql->error);
        $res = $sql->get_result();
        
        $departmentsIn = array();

        while($row = $res->fetch_assoc()) {
            $departmentsIn[] = $row['department'];
        }

        foreach($departments as $department) {
            if(!in_array($department, $departmentsIn)) {
                $sql = $this->db->prepare('INSERT INTO employees_departments (employee, department) VALUES (?, ?)');
                $sql->bind_param('ii', $employee, $department);
                $sql->execute();
                printf($sql->error);
            }
        }
    }
}

$database = new Database();
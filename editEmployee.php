<?php include './templates/header.php'; ?>
<?php include './auth.php'; ?>
<?php
    require_once './server/Database.php';

    $employeeObj;
    $departments;
    $employee;

    if (!empty($_REQUEST['id'])) {

        $employee = $database->getEmployee($_REQUEST['id']);
        $employeeObj = $employee->fetch_object();
        $departments = $database->getDepartments($_SESSION['idUser']);

        if (!empty($_POST['submit'])) {
            $firstName = htmlspecialchars($_POST['firstName']);
            $lastName = htmlspecialchars($_POST['lastName']);
            $deps = $_POST['departments'];
            $database->updateEmployee($_REQUEST['id'], $firstName, $lastName, $deps);
            header('Location: /employees.php');
        }
    }
?>
<section class="update-employee">
    <h4>Edit employee</h4>
    <form action="editEmployee.php?id=<?php echo $_REQUEST['id']; ?>" method="post">
        <label for="firstName">First name</label>
        <input type="text" name="firstName" value="<?php echo $employeeObj->firstName ?>" required>
        <label for="lastName">Last name</label>
        <input type="text" name="lastName" value="<?php echo $employeeObj->lastName ?>" required>
        <label for="departments">Departments</label>
        <?php 
            while($row = $departments->fetch_assoc()) {
                $selected = false;
                $employee = $database->getEmployee($_REQUEST['id']);

                while($dep = $employee->fetch_assoc()) {
                    if(intval($row['id']) == intval($dep['depId'])) {
                        $selected = true;
                    }
                }

                if($selected) {
                    echo '<input type="checkbox" name="departments[]" value="'.$row['id'].'" checked>'.$row['name'].'</option>';
                } else {
                    echo '<input type="checkbox" name="departments[]" value="'.$row['id'].'">'.$row['name'].'</option>';
                }
            }
        ?>
        <input type="submit" name="submit" value="Edit">
    </form>
</section>
<?php include './templates/footer.php'; ?>
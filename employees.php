<?php include './templates/header.php'; ?>
<?php include './auth.php'; ?>
<?php
    require_once './server/Database.php';

    $employees = $database->getEmployees($_SESSION['idUser']);

    if(!empty($_REQUEST['department'])) {
        if($_REQUEST['department'] != "null") {
            $employees = $database->getEmployees($_SESSION['idUser'], $_REQUEST['department']);
        }
    }

    $departments = $database->getDepartments($_SESSION['idUser']);

    if (!empty($_POST['submit'])) {
        $firstName = htmlspecialchars($_POST['firstName']);
        $lastName = htmlspecialchars($_POST['lastName']);
        $departments = $_POST['departments'];
        $database->addEmployee($firstName, $lastName, $departments, $_SESSION['idUser']);
        header('Location: /employees.php');
    }

    if(!empty($_POST['filter'])) {
        $department = $_POST['department'];
        header('Location: /employees.php?department='.$department);
    }
?>
<section class="employees">
    <h4>Employees</h4>
    <h5>Add employee</h5>
    <form action="employees.php" method="post">
        <label for="firstName">First name</label>
        <input type="text" name="firstName" required>
        <label for="lastName">Last name</label>
        <input type="text" name="lastName" required>
        <label for="departments">Departments</label>
        <?php 
            while($row = $departments->fetch_assoc()) {
                echo '<input type="checkbox" name="departments[]" value="'.$row['id'].'">'.$row['name'].'</input>';
            }
        ?>
        <input type="submit" name="submit" value="Add">
    </form>
    <br>
    <form action="employees.php" method="post">
        <label for="department">Filter by department</label>
        <select name="department">
                <option value="null">None</option>
                <?php
                    $departments = $database->getDepartments($_SESSION['idUser']);
                    while($row = $departments->fetch_assoc()) {

                        echo '<option value="'.$row['id'].'">'.$row['name'].'</option>';
                    }
                ?>
        </select>
        <input type="submit" name="filter" value="Filter">
    <form>
    <br>
    <h5>Employees table</h5>
    <table>
        <tr>
            <th>First name</th>
            <th>Last name</th>
            <th>Actions</th>
        </tr>
            <?php
                while ($row = $employees->fetch_assoc()) {
                    echo '<tr>';
                    echo '<td>'.$row['firstName'].'</td>';
                    echo '<td>'.$row['lastName'].'</td>';
                    echo 
                        '<td>
                            <a href="/employee.php?id='.$row['id'].'">View</a>
                            <a href="/editEmployee.php?id='.$row['id'].'">Edit</a>
                            <a href="/removeEmployee.php?id='.$row['id'].'">Remove</a>
                        </td>';
                    echo '</tr>';
                }

                $employees->free();
            ?>
    </table>
</section>
<?php include './templates/footer.php'; ?>
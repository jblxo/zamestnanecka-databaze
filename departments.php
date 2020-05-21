<?php include './templates/header.php'; ?>
<?php include './auth.php'; ?>
<?php
    require_once './server/Database.php';

    $departments = $database->getDepartments($_SESSION['idUser']);

    if (!empty($_POST['submit'])) {
        $name = htmlspecialchars($_POST['name']);
        $abbreviation = htmlspecialchars($_POST['abbreviation']);
        $city = htmlspecialchars($_POST['city']);
        $color = htmlspecialchars($_POST['color']);
        $database->addDepartment($name, $abbreviation, $city, $color, $_SESSION['idUser']);
        header('Location: /departments.php');

    }
?>
<section class="departments">
    <h4>Departments</h4>
    <h5>Add department</h5>
    <form action="departments.php" method="post">
        <label for="name">Name</label>
        <input type="text" name="name" required>
        <label for="abbreviation">Abbreviation</label>
        <input type="text" name="abbreviation" required>
        <label for="city">City</label>
        <input type="text" name="city" required>
        <label for="color">Color</label>
        <input type="color" name="color" value="#ff0000" required>
        <input type="submit" name="submit" value="Add">
    </form>
    <br>
    <h5>Departments table</h5>
    <table>
        <tr>
            <th>Name</th>
            <th>Abbreviation</th>
            <th>City</th>
            <th>Employees</th>
            <th>Actions</th>
        </tr>
            <?php
                while ($row = $departments->fetch_assoc()) {
                    echo '<tr style="background: '.$row['color'].'">';
                    echo '<td>'.$row['name'].'</td>';
                    echo '<td>'.$row['abbreviation'].'</td>';
                    echo '<td>'.$row['city'].'</td>';
                    echo '<td>'.$row['employeeCount'].'</td>';
                    echo 
                        '<td>
                            <a href="/department.php?id='.$row['id'].'">View</a>
                            <a href="/editDepartment.php?id='.$row['id'].'">Edit</a>
                            <a href="/removeDepartment.php?id='.$row['id'].'">Remove</a>
                        </td>';
                    echo '</tr>';
                }

                $departments->free();
            ?>
    </table>
</section>
<?php include './templates/footer.php'; ?>
<?php include './templates/header.php'; ?>
<?php include './auth.php'; ?>
<?php
    require_once './server/Database.php';

    $department;

    if (!empty($_REQUEST['id'])) {

        $department = $database->getDepartmentWithEmployees($_REQUEST['id']);
        $obj = $department->fetch_object();
        $department = $database->getDepartmentWithEmployees($_REQUEST['id']);
    }
?>
<section class="department">
    <h4>Department</h4>
    <ul>
        <li>Name: <?php echo $obj->name ?></li>
        <li>City: <?php echo $obj->city ?></li>
        <li>Abbreviation: <?php echo $obj->abbreviation ?></li>
    </ul>
    <br>
    <table>
        <tr>
            <th>First name</th>
            <th>Last name</th>
            <th>Created at</th>
        </tr>
            <?php
                while ($row = $department->fetch_assoc()) {
                    echo '<tr>';
                    echo '<td>'.$row['firstName'].'</td>';
                    echo '<td>'.$row['lastName'].'</td>';
                    echo '<td>'.$row['createdAt'].'</td>';
                    echo '</tr>';
                }

                $department->free();
            ?>
    </table>
</section>
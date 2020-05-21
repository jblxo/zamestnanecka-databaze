<?php include './templates/header.php'; ?>
<?php include './auth.php'; ?>
<?php
    require_once './server/Database.php';

    $employee;

    if (!empty($_REQUEST['id'])) {

        $employee = $database->getEmployee($_REQUEST['id']);
        $obj = $employee->fetch_object();
        $employee = $database->getEmployee($_REQUEST['id']);
    }
?>
<section class="employee">
    <h4>Employee</h4>
    <ul>
        <li>First name: <?php echo $obj->firstName ?></li>
        <li>Last name: <?php echo $obj->lastName ?></li>
    </ul>
    <br>
    <table>
        <tr>
            <th>Name</th>
        </tr>
            <?php
                while ($row = $employee->fetch_assoc()) {
                    echo '<tr style="background: '.$row['color'].'">';
                    echo '<td>'.$row['name'].'</td>';
                    echo '</tr>';
                }

                $employee->free();
            ?>
    </table>
</section>
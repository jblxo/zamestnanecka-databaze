<?php include './templates/header.php'; ?>
<?php include './auth.php'; ?>
<?php
    require_once './server/Database.php';

    if (!empty($_REQUEST['id'])) {

        $department = $database->getDepartment($_REQUEST['id']);

        if (!empty($_POST['submit'])) {
            $name = htmlspecialchars($_POST['name']);
            $abbreviation = htmlspecialchars($_POST['abbreviation']);
            $city = htmlspecialchars($_POST['city']);
            $color = htmlspecialchars($_POST['color']);
            $database->updateDepartment($name, $abbreviation, $city, $color, $_REQUEST['id']);
            header('Location: /departments.php');
        }

        while($row = $department->fetch_assoc()) {
            echo '
            <section class="edit-department">
            <h4>Edit department</h4>
            <form action="editDepartment.php?id='.$_REQUEST['id'].'" method="post">
            <label for="name">Name</label>
            <input type="text" name="name" value="'.$row['name'].'" required>
            <label for="abbreviation">Abbreviation</label>
            <input type="text" name="abbreviation" value="'.$row['abbreviation'].'" required>
            <label for="city">City</label>
            <input type="text" name="city" value="'.$row['city'].'" required>
            <label for="color">Color</label>
            <input type="color" name="color" value="'.$row['color'].'" required>
            <input type="submit" name="submit" value="Edit">
            </form>
            </section>
            ';
        }
    }
?>
<?php include './templates/footer.php'; ?>
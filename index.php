<?php
$errors = "";

$db = mysqli_connect("localhost", "root", "", "todo_list");

if (isset($_POST['submit'])) {
    if (empty($_POST['task'])) {
        $errors = "*You must fill in the task";
    } else {
        $task = $_POST['task'];
        $sql = "INSERT INTO tasks (task) VALUES ('$task')";
        mysqli_query($db, $sql);
        header('location: index.php');
    }
}

if (isset($_GET['favorite_task'])) {
    $id = $_GET['favorite_task'];
    mysqli_query($db, "UPDATE tasks SET favorite = 1 WHERE id=".$id);
    header('location: index.php');
}

if (isset($_GET['unfavorite_task'])) {
    $id = $_GET['unfavorite_task'];
    mysqli_query($db, "UPDATE tasks SET favorite = 0 WHERE id=".$id);
    header('location: index.php');
}

if (isset($_GET['del_task'])) {
    $id = $_GET['del_task'];
    mysqli_query($db, "DELETE FROM tasks WHERE id=".$id);
    header('location: index.php');
}

$favoriteTasks = mysqli_query($db, "SELECT * FROM tasks WHERE favorite = 1");
$nonFavoriteTasks = mysqli_query($db, "SELECT * FROM tasks WHERE favorite = 0");

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ToDo List</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container mt-5">
        <h1 class="mb-4 text-center">ToDo List</h1>
        <form method="post" action="index.php">
            <div class="input-group mb-3">
                <input type="text" name="task" class="form-control" placeholder="Enter a task">
                <div class="input-group-append">
                    <button type="submit" name="submit" class="btn btn-primary">Add Task</button>
                </div>
            </div>
            <?php if (isset($errors)) { ?>
                <p class="text-danger mb-2"><?php echo $errors; ?></p>
            <?php } ?>
        </form>
        <div class="container mt-5">
        <div class="row">
            <div class="col-md-12">
                <h3>Favorites</h3>
                <ul class="task-list">
                    <?php while ($row = mysqli_fetch_array($favoriteTasks)) { ?>
                        <li class="task-item d-flex justify-content-between align-items-center">
                            <span><?php echo $row['task']; ?></span>
                            <div class="task-actions">
                                <a href="index.php?unfavorite_task=<?php echo $row['id']; ?>"><i class="fas fa-star task-favorite"></i></a>
                                <a href="index.php?del_task=<?php echo $row['id']; ?>"><i class="fas fa-trash"></i></a>
                            </div>
                        </li>
                    <?php } ?>
                </ul>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <h3>Inbox</h3>
                <ul class="task-list">
                    <?php while ($row = mysqli_fetch_array($nonFavoriteTasks)) { ?>
                        <li class="task-item d-flex justify-content-between align-items-center">
                            <span><?php echo $row['task']; ?></span>
                            <div class="task-actions">
                                <?php if ($row['favorite'] == 1) { ?>
                                    <a href="index.php?unfavorite_task=<?php echo $row['id']; ?>"><i class="fas fa-star task-favorite"></i></a>
                                <?php } else { ?>
                                    <a href="index.php?favorite_task=<?php echo $row['id']; ?>"><i class="far fa-star"></i></a>
                                <?php } ?>
                                <a href="index.php?del_task=<?php echo $row['id']; ?>"><i class="fas fa-trash"></i></a>
                            </div>
                        </li>
                    <?php } ?>
                </ul>
            </div>
        </div>
    </div>
</body>
</html>
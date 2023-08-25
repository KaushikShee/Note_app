<?php 
session_start(); // starting session

$server = "localhost";
$user = "root";
$password = "";
$database = "notes";

$connect = mysqli_connect($server, $user, $password, $database); // connect to database
if (!$connect) {
    die("Connection failed: " . mysqli_connect_error());
}


// Handling Deletion
if(isset($_GET['delete'])) {
    $sno = $_GET['delete'];
    $sql = "DELETE FROM `notes` WHERE `notes`.`sno` = $sno";
    $res = mysqli_query($connect, $sql);
    if ($res) {
        $_SESSION['delete_message'] = " Your note has been deleted successfully.";
        header("Location: index.php");
        exit();
    }
}

// Handling Updation
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['snoEdit'])) {
        $title = $_POST['titleEdit'];
        $description = $_POST['descriptionEdit'];
        $sno = $_POST['snoEdit'];
        if (!empty($title) && !empty($description)) {
            $sql = "UPDATE `notes` SET `title` = '$title' , `description` = '$description' WHERE  `notes`.`sno`='$sno'";
            $res = mysqli_query($connect, $sql);
            if ($res) {
                $_SESSION['update_message'] = " Your note has been updated successfully.";
                header("Location: index.php");
                exit();
            }
        }
    }

    // Handling Insertion
    else {
        $title = $_POST['title'];
        $description = $_POST['description'];
        if (!empty($title) && !empty($description)) {
            $sql = "INSERT INTO `notes`(`title`, `description`) VALUES ('$title', '$description')";
            $res = mysqli_query($connect, $sql);
            if ($res) {
                $_SESSION['reg_message'] = " Your note has been added successfully.";
                header("Location: index.php");
                exit();
            }
        }
    }
}
?>

<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/css/bootstrap.min.css"
        integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <link rel="stylesheet" href="//cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <title>PHP Note</title>

</head>

<body>
    <!-- Button trigger modal -->
    <!-- <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">
        Edit Modal
    </button> -->

    <!-- Modal -->
    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Modify Notes</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="post">
                        <input type="hidden" name="snoEdit" id="snoEdit">
                        <div class="form-group">
                            <label for="title">Title</label>
                            <input type="text" class="form-control" id="titleEdit" name="titleEdit">
                        </div>
                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea class="form-control" id="descriptionEdit" name="descriptionEdit"
                                rows="4"></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Update</button>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed">
        <a class="navbar-brand" href="#">Notes</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavAltMarkup"
            aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
            <div class="navbar-nav">
                <a class="nav-item nav-link active" href="#">Home <span class="sr-only">(current)</span></a>
                <a class="nav-item nav-link" href="#">Features</a>
                <a class="nav-item nav-link" href="#">Pricing</a>
                <a class="nav-item nav-link disabled" href="#" tabindex="-1" aria-disabled="true">Disabled</a>
            </div>
        </div>
    </nav>

    <?php
        if (isset($_SESSION['reg_message'])) {
            echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>Success!</strong>' . $_SESSION['reg_message'] . '
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
            </div>';
            unset($_SESSION['reg_message']); 
        }

        if (isset($_SESSION['update_message'])) {
            echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>Success!</strong>' . $_SESSION['update_message'] . '
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
            </div>';
            unset($_SESSION['update_message']); 
        }

        if (isset($_SESSION['delete_message'])) {
            echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
                <strong>Success!</strong> ' . $_SESSION['delete_message'] . '
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>';
            unset($_SESSION['delete_message']);
        }
    ?>

    <div class="container my-5">
        <h2>Add a Note</h2>
        <form method="post">
            <div action="index.php" class="form-group">
                <label for="title">Title</label>
                <input type="text" class="form-control" id="title" name="title">
            </div>
            <div class="form-group">
                <label for="description">Description</label>
                <textarea class="form-control" id="description" name="description" rows="4"></textarea>
            </div>
            <div class="mb-3">
                <label for="formFile" class="form-label">Add File</label>
                <input class="form-control" type="file" name="file" id="file">
            </div>
            <button type="submit" class="btn btn-primary">Add</button>
        </form>
    </div>

    <div class="container">
        <h2>Previous Notes</h2>
        <table class="table" id="myTable">
            <thead>
                <tr>
                    <th scope="col">S. No</th>
                    <th scope="col">Title</th>
                    <th scope="col">Details</th>
                    <th scope="col">Modify</th>
                </tr>
            </thead>
            <tbody>
                <?php
              $sql = "SELECT * FROM `notes`";
              $res = mysqli_query($connect, $sql);
              $sno = 0;
              while($row = mysqli_fetch_assoc($res)) {
                $sno = $sno+1;
                echo "<tr>
                <th scope='row'>".$sno."</th>
                <td>".$row['title']."</td>
                <td>".$row['description']."</td>
                <td><button class='edit btn btn-sm btn-primary' id=".$row['sno'].">Edit</button> <button class='delete btn btn-sm btn-primary' id=d".$row['sno']." onclick='confirmDelete(".$row['sno'].")'>Delete</button></td>
              </tr>";
                echo "<br>";
              }
            ?>
            </tbody>
        </table>
    </div>
    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js"
        integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"
        integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/js/bootstrap.min.js"
        integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous">
    </script>
    <script src="//cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <<script>
        let table = new DataTable('#myTable');
        </script>
        <script>
        edits = document.getElementsByClassName('edit');
        Array.from(edits).forEach((element) => {
            element.addEventListener("click", (e) => {
                tr = e.target.parentNode.parentNode;
                title = tr.getElementsByTagName("td")[0].innerText;
                description = tr.getElementsByTagName("td")[1].innerText;
                console.log(title, description);
                titleEdit.value = title;
                descriptionEdit.value = description;
                snoEdit.value = e.target.id;
                console.log(e.target.id);
                $('#editModal').modal('toggle');
            })
        })

        deletes = document.getElementsByClassName('delete');
        Array.from(deletes).forEach((element) => {
            element.addEventListener("click", (e) => {
                sno = e.target.id.substr(1, );
                if (confirm("Are you sure!")) {
                    console.log("yes");
                    window.location = `index.php?delete=${sno}`;
                } else {
                    console.log("no");
                }
            })
        })
        </script>
        <script>
            function confirmDelete(sno) {
                if (confirm("Are you sure you want to delete this note?")) {
                    window.location = `index.php?delete=${sno}`;
                }
            }
        </script>
</body>

</html>
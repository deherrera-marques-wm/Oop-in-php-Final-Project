<?php
require 'Databases.php';
require 'tags.php';

$database = new Database;
$post = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

//$database->query('SELECT * FROM posts');

//print_r($rows);

if(@$_POST['delete_id']){
    $delete_id = $_POST['delete_id'];
    $database->query('DELETE FROM posts WHERE id = :id');
    $database->bind(':id', $delete_id);
    $database->execute();


}
if(@$post['update']){
    $id = $post['id'];
    $first_name = $post['first_name'];
    $title = $post['title'];
    $body = $post['body'];

    $database->query('UPDATE posts SET first_name = :first_name, title = :title, body = :body WHERE id = :id');
    $database->bind(':first_name', $first_name);
    $database->bind(':title', $title);
    $database->bind(':body',$body);
    $database->bind(':id', $id);
    $database->execute();

}


if(@$post['submit']) {
    $first_name = $post['first_name'];
    $title = $post['title'];
    $body = $post['body'];

    $database->query('INSERT INTO posts (first_name, title, body) VALUES(:first_name, :title, :body)');
    $database->bind(':first_name', $first_name);
    $database->bind(':title', $title);
    $database->bind(':body', $body);
    // $database->bind(':id', 1);
    $database->execute();
    if($database->lastInsertId()){
        echo '<p>Post Added</p>';
    }
}



$database->query('SELECT * FROM posts');
$rows = $database->resultset();

?>
<!DOCTYPE html>
<html>
<title>My Blog</title>
<head>
    <link rel="stylesheet" type="text/css" href="StyleSheet.css">
</head>
<body>

<h1>Add Post</h1>

<form method="post" action="<?php $_SERVER['PHP_SELF']; ?>">
    <label>Your Name</label><br />
    <input type="text" name="first_name" placeholder="First Name"/><br/><br/>
    <label>Post Title</label><br />
    <input type="text" name="title" placeholder="Add a Title..." /><br/><br />
    <label>Post Body</label><br/>
    <textarea name="body"></textarea><br/><br/>
    <input type="submit" name="submit" value="Submit"/>
</form>

<h1>Posts</h1>
<div>
    <?php
    foreach($rows as $row) {
        ?>
        <div>
            <h1 style="color: white"><?php echo $row['first_name'];?></h1>
            <h3><?php echo $row['title'];?></h3>
            <p><?php echo $row['body'];?></p>
            <p><?php echo $row['date_posted']; ?></p>
            <form method="post" action="<?php $_SERVER['PHP_SELF']; ?>">
                <input type="hidden" name="delete_id" value="<?php echo $row['id'];?>">
                <input type="submit" name="delete" value="Delete">
            </form>
        </div>

    <?php } ?>
</div>
</body>
</html>
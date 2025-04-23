<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    echo "<script>window.location.href='login.php';</script>";
    exit();
}

$user_id = $_SESSION['user_id'];
$user = $conn->query("SELECT * FROM users WHERE id=$user_id")->fetch_assoc();

// Get the number of friends
$friends_count = $conn->query("SELECT COUNT(*) as count FROM friends WHERE user_id=$user_id OR friend_id=$user_id")->fetch_assoc();
$friends_count = $friends_count['count'] / 2;

// Fetch user posts
$posts = $conn->query("SELECT posts.*, users.name FROM posts JOIN users ON posts.user_id = users.id ORDER BY posts.id DESC");

// Handle new post submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['content'])) {
    $content = htmlspecialchars($_POST['content']);
    $conn->query("INSERT INTO posts (user_id, content) VALUES ($user_id, '$content')");
    echo "<script>window.location.href='index.php';</script>";
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Facebook Clone - Home</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f0f2f5;
            margin: 0;
        }
        .navbar {
            background-color: #1877f2;
            color: white;
            padding: 15px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .navbar h1 {
            margin: 0;
            font-size: 24px;
        }
        .navbar a {
            background: white;
            color: #1877f2;
            text-decoration: none;
            padding: 8px 16px;
            border-radius: 8px;
            font-weight: bold;
        }
        .container {
            max-width: 700px;
            margin: 30px auto;
            background: white;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .profile-section {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .profile-info {
            display: flex;
            align-items: center;
        }
        .profile-info img {
            width: 70px;
            height: 70px;
            border-radius: 50%;
            margin-right: 15px;
        }
        .profile-info h2 {
            margin: 0;
        }
        .post-form {
            margin-top: 20px;
        }
        .post-form textarea {
            width: 100%;
            height: 70px;
            padding: 10px;
            border-radius: 8px;
            border: 1px solid #ccc;
            resize: none;
        }
        .button {
            margin-top: 10px;
            background: #1877f2;
            color: white;
            padding: 8px 20px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
        }
        .button:hover {
            background: #145db2;
        }
        .post {
            background: #fff;
            padding: 15px;
            margin-top: 20px;
            border-radius: 10px;
            box-shadow: 0 0 5px #ccc;
        }
        .post h4 {
            color: #1877f2;
            margin: 0 0 8px;
        }
        .post p {
            color: #333;
        }
        .comment-box {
            margin-top: 10px;
        }
        .comment-box input {
            width: 75%;
            padding: 6px;
            border: 1px solid #ccc;
            border-radius: 6px;
        }
        .comment-box button {
            padding: 6px 12px;
            background-color: #1877f2;
            color: white;
            border: none;
            border-radius: 6px;
        }
    </style>
</head>
<body>

<div class="navbar">
    <h1>Facebook Clone</h1>
    <div>
        <a href="profile.php">Profile</a>
        <a href="logout.php" style="margin-left: 10px;">Logout</a>
    </div>
</div>

<div class="container">
    <div class="profile-section">
        <div class="profile-info">
            <img src="<?= isset($user['profile_pic']) && $user['profile_pic'] != '' ? $user['profile_pic'] : 'uploads/default.jpg' ?>" alt="Profile">
            <div>
                <h2><?= htmlspecialchars($user['name']) ?></h2>
                <p>Friends: <?= $friends_count ?></p>
            </div>
        </div>
    </div>

    <form class="post-form" method="POST">
        <textarea name="content" placeholder="What's on your mind?" required></textarea>
        <br>
        <button class="button" type="submit">Post</button>
    </form>

    <?php while ($row = $posts->fetch_assoc()) { ?>
        <div class="post">
            <h4><?= htmlspecialchars($row['name']) ?></h4>
            <p><?= nl2br(htmlspecialchars($row['content'])) ?></p>

            <form method="GET" action="like.php" style="display:inline;">
                <input type="hidden" name="post_id" value="<?= $row['id'] ?>">
                <button class="button" style="padding: 4px 10px;">❤️ Like</button>
            </form>

            <?php
            $post_id = $row['id'];
            $likes = $conn->query("SELECT COUNT(*) as count FROM likes WHERE post_id=$post_id")->fetch_assoc();
            echo "<span style='margin-left:10px;'>{$likes['count']} Likes</span>";
            ?>

            <div class="comment-box">
                <form method="POST" action="comment.php">
                    <input type="hidden" name="post_id" value="<?= $row['id'] ?>">
                    <input type="text" name="comment" placeholder="Write a comment..." required>
                    <button type="submit">Comment</button>
                </form>
            </div>
        </div>
    <?php } ?>
</div>

</body>
</html>

<?php

function getPosts() {
	// We connect to the database.
	$database = dbConnect();

	// We retrieve the 5 last blog posts.
	$statement = $database->query(
    	"SELECT id, titre, contenu, DATE_FORMAT(date_creation, '%d/%m/%Y à %Hh%imin%ss') AS date_creation_fr FROM billets ORDER BY date_creation DESC LIMIT 0, 5"
	);
	$posts = [];
	while (($row = $statement->fetch())) {
    	$post = [
        	'title' => $row['titre'],
        	'french_creation_date' => $row['date_creation_fr'],
        	'content' => $row['contenu'],
			'identifier' => $row['id'],
    	];

    	$posts[] = $post;
	}

	return $posts;
}

function getPost($identifier) {
	$database = dbConnect();

	$statement = $database->prepare("SELECT id, title, content, DATE_FORMAT(creation_date, '%d/%m/%Y à %Hh%min%ss') AS french_creation_date FROM posts WHERE id = ?");
	$statement->execute([$identifier]);

	$row = $statement->fetch();
	$post = [
		'title' => $row['title'],
		'french_creation_date' => $row['french_creation_date'],
		'content' => $row['content'],
		'identifier' => $row['id'],
	];

	return $post;

}

function getComments($identifier) {
	$database = dbConnect();
	
	$statement = $database->prepare("SELECT id, author, comment, DATE_FORMAT(comment_date, '%d/%m/%Y à %Hh%imin%ss') AS french_creation_date FROM comments WHERE post_id = ? ORDER BY comment_date DESC");
	$statement->execute($identifier);

	$comments = [];

	while (($row = $statement->fetch())) {
		$comment = [
			'author' => $row['author'],
			'french_creation_date' => $row['french_creation_date'],
			'comment' => $row['comments'],
		];

		$comments[] = $comment;
	}

	return $comments;
}


function dbConnect() {
	try{
		$database = new PDO('mysql:host=localhost;dbname=blog;charset=utf8', 'blog-open', '');		
		return $database;
	} catch(Exception $e) {
		die('Erreur: '.$e->getMessage());
	}
}


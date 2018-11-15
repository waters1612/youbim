<?php
require_once('Config.php');

class SQLiteConnection{

	public $database;

	public function __construct()
	{
		$this->database = new SQLite3(Config::PATH_TO_SQLITE_FILE);
		$query = 'CREATE TABLE IF NOT EXISTS usuarios(
			id INTEGER PRIMARY KEY AUTOINCREMENT,
			nombre VARCHAR,
			apellido VARCHAR,
			email VARCHAR CONSTRAINT unique_email UNIQUE,
			telefono INTEGER(10)
			);';
		$this->database->exec($query);
	}
}
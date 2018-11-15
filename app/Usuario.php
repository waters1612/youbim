<?php
require_once('SQLiteConnection.php');

class Usuario{

	public $nombre;
	public $apellido;
	public $email;
	public $telefono;
	public $objDB;

	public function __construct()
	{
		$this->objDB = new SQLiteConnection();
	}

	public function setNombre($nombre)
	{
		$this->nombre = $nombre;
	}

	public function setApellido($apellido)
	{
		$this->apellido = $apellido;
	}

	public function setEmail($email)
	{
		$this->email = $email;
	}

	public function setTelefono($telefono)
	{
		$this->telefono = $telefono;
	}

	public function getNombre()
	{
		return $this->nombre;
	}

	public function getApellido()
	{
		return $this->apellido;
	}

	public function getEmail()
	{
		return $this->email;
	}

	public function getTelefono()
	{
		return $this->telefono;
	}

	public function validateUser($objUser)
	{
		$msg = "";

		if(!is_null($objUser))
		{
			$this->setNombre($objUser->nombre);
			$this->setApellido($objUser->apellido);
			$this->setEmail($objUser->email);
			$this->setTelefono($objUser->telefono);
		}

		$nombre = $this->getNombre();
		$apellido = $this->getApellido();
		$email = $this->getEmail();
		$telefono = $this->getTelefono();

		if(!is_null($nombre) && !is_null($apellido) && !is_null($email) && !is_null($telefono))
		{
			if (!preg_match('/^[a-zA-ZáéíóúüÁÉÍÓÚÜñÑ]+$/', $nombre))
			{
				$msg .= "Formato de nombre no valido".', ';
			}
			if(!(preg_match('/^[a-zA-ZáéíóúüÁÉÍÓÚÜñÑ]+$/',$apellido)))
			{
				$msg .= "Formato de apellido no valido".', ';
			}
			if(!preg_match("/^[0-9]+$/", $telefono))
			{
				$msg .= "Formato de telefono no valido".', ';
			}
			if(!filter_var($email,FILTER_VALIDATE_EMAIL))
			{
				$msg .= "Formato de email no valido".', ';
			}
		}else
		{
			$msg .= "Debe ingresar todos los campos";
		}

		return $msg;
	}

	public function store($objUser)
	{
		$msg = $this->checkUserByEmail($objUser->email);

		if(!is_null($msg))
		{
			return $msg;
		}else
		{
			$query = "INSERT INTO usuarios ('nombre','apellido','email','telefono') VALUES (:nombre,:apellido,:email,:telefono);";
			$statement = $this->objDB->database->prepare($query);
			$statement->bindValue(':nombre',$objUser->nombre,SQLITE3_TEXT);
			$statement->bindValue(':apellido',$objUser->apellido,SQLITE3_TEXT);
			$statement->bindValue(':email',$objUser->email,SQLITE3_TEXT);
			$statement->bindValue(':telefono',$objUser->telefono,SQLITE3_INTEGER);

			if($statement->execute())
			return 'OK';
		}
	}

	public function checkUserByEmail($email)
	{
		$msg = null;
		$statement = $this->objDB->database->prepare('Select * from usuarios where email = :email;');
		$statement->bindValue(':email',$email);

		$result = $statement->execute();

		if($result->fetchArray())
		{
			$msg = 'Ya existe un usuario con este email';
		}

		return $msg;
	}

	public function getAllUsers()
	{
		$users = Array();
		$statement = $this->objDB->database->prepare('Select * from usuarios;');

		if($statement)
		{
			$result = $statement->execute();

			while($reg = $result->fetchArray())
			{
				$users[] = Array(
							'nombre' => $reg['nombre'],
							'apellido' => $reg['apellido'],
							'email' => $reg['email'],
							'telefono' => $reg['telefono']
							);
			}
		}
		return $users;
	}

	public function getUser($id)
	{
		$user;

		$statement = $this->objDB->database->prepare('Select * from usuarios where id = :id;');
		$statement->bindValue(':id',$id,SQLITE3_INTEGER);

		if($statement)
		{
			$result = $statement->execute();
			while($reg = $result->fetchArray())
			{
				$user = Array(
							'nombre' => $reg['nombre'],
							'apellido' => $reg['apellido'],
							'email' => $reg['email'],
							'telefono' => $reg['telefono']
							);
			}
		}
		return $user;
	}
}
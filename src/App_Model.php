<?php
namespace Model;

class App_Model {

	/** @var object Database connection */
	private $conn;

	/**
	 * Instantiate the model class.
	 *
	 * @param object $db_connection DB connection
	 */
	public function __construct( $db_connection ) {

		$this->conn = $db_connection;
	}

	/**
	 * Check if a HybridAuth identifier already exist in DB
	 *
	 * @param $identifier
	 *
	 * @return bool
	 */
	public function identifier_exist( $identifier ) {
		try {
			$sql    = 'SELECT identifier FROM users';
			$query  = $this->conn->query( $sql );
			$result = $query->fetchAll( \PDO::FETCH_COLUMN, 0 );

			return in_array( $identifier, $result );
		} catch ( \PDOException $e ) {

			die( $e->getMessage() );
		}

	}

	/**
	 * Save users record to the database.
	 *
	 * @param string $identifier user's unique identifier
	 * @param string $email
	 * @param $first_name
	 * @param $last_name
	 * @param $avatar_url
	 *
	 * @return bool
	 */
	public function register_user( $identifier, $email, $first_name, $last_name, $avatar_url ) {

		try {
			$sql = "INSERT INTO users (identifier, email, first_name, last_name, avatar_url) VALUES (:identifier, :email, :first_name, :last_name, :avatar_url)";

			$query = $this->conn->prepare( $sql );
			$query->bindValue( ':identifier', $identifier );
			$query->bindValue( ':email', $email );
			$query->bindValue( ':first_name', $first_name );
			$query->bindValue( ':last_name', $last_name );
			$query->bindValue( ':avatar_url', $avatar_url );

			return $query->execute();
		} catch ( \PDOException $e ) {
			return $e->getMessage();
		}
	}


	/**
	 * Create user login session
	 *
	 * @param $identifier
	 */
	public function login_user( $identifier ) {
		\Hybrid_Auth::storage()->set( 'user', $identifier );
	}


	/** Destroy user login session */
	public function logout_user() {
		\Hybrid_Auth::storage()->set( 'user', null );
	}

	/**
	 * Return user's first name.
	 *
	 * @param $identifier
	 *
	 * @return string
	 */
	public function getFirstName( $identifier ) {
		if ( ! isset( $identifier ) ) {
			return;
		}
		$query  = $this->conn->query( "SELECT first_name FROM users WHERE identifier = $identifier" );
		$result = $query->fetch( \PDO::FETCH_NUM );

		return $result[0];
	}


	/**
	 * Return user's last name.
	 *
	 * @param $identifier
	 *
	 * @return mixed
	 */
	public function getLastName( $identifier ) {
		if ( ! isset( $identifier ) ) {
			return;
		}
		$query  = $this->conn->query( "SELECT last_name FROM users WHERE identifier = $identifier" );
		$result = $query->fetch( \PDO::FETCH_NUM );

		return $result[0];
	}

	/**
	 * Return user's email address
	 *
	 * @param $identifier
	 *
	 * @return mixed
	 */
	public function getEmail( $identifier ) {
		if ( ! isset( $identifier ) ) {
			return;
		}
		$query  = $this->conn->query( "SELECT email FROM users WHERE identifier = $identifier" );
		$result = $query->fetch( \PDO::FETCH_NUM );

		return $result[0];
	}


	/**
	 * Return the URL of user's avatar
	 *
	 * @param $identifier
	 *
	 * @return mixed
	 */
	public function getAvatarUrl( $identifier ) {
		if ( ! isset( $identifier ) ) {
			return;
		}
		$query  = $this->conn->query( "SELECT avatar_url FROM users WHERE identifier = $identifier" );
		$result = $query->fetch( \PDO::FETCH_NUM );

		return $result[0];
	}

}
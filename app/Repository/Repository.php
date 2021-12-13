<?php

declare(strict_types=1);

namespace App\Repository;

use Nette;
use Nette\Database\Table\ActiveRow;
use Nette\Database\Table\Selection;

/**
 * Provadi operace nad databazovou tabulkou
 */
abstract class Repository
{
	use Nette\SmartObject;

	/** @var string */
	protected $tableName;

	/** @var Nette\Database\Explorer */
	private $dbExplorer;


	/**
	 * @param Nette\Database\Explorer $dbExplorer
	 * @throws Nette\InvalidStateException
	 */
	public function __construct(Nette\Database\Explorer $dbExplorer)
	{
		$this->dbExplorer = $dbExplorer;

		if ($this->tableName === null) {
			$class = static::class;

			throw new Nette\InvalidStateException("Table name must be defined in $class::\$tableName.");
		}
	}


	/**
	 * Metoda pro vykonani dotazu pro vice zaznamu
	 *
	 * @param string $sStmt - statemant
	 * @return Nette\Database\ResultSet
	 */
	protected function query($sStmt): mixed
	{
		return call_user_func_array([$this->dbExplorer->getConnection(), 'query'], func_get_args());
	}


	protected function queryFetch(): mixed
	{
		return call_user_func_array([$this->dbExplorer->getConnection(), 'fetch'], func_get_args());
	}


	/**
	 * Vraci celou tabulku z databaze
	 * @return Nette\Database\Table\Selection
	 */
	public function getTable()
	{
		return $this->dbExplorer->table($this->tableName);
	}


	/**
	 * Vraci vsechny zaznamy v tabulce
	 * @return Nette\Database\Table\Selection
	 */
	public function findAll()
	{
		return $this->getTable();
	}


	/**
	 * Vrací vyfiltrované záznamy na základě vstupního pole
	 * (pole array('name' => 'David') se převede na část SQL dotazu WHERE name = 'David')
	 * @param array<string, mixed> $by
	 * @return Nette\Database\Table\Selection
	 */
	public function findBy(array $by): Nette\Database\Table\Selection
	{
		return $this->getTable()->where($by);
	}


	/**
	 * To samé jako findBy akorát vrací vždy jen jeden záznam
	 * @param array<string, mixed> $by
	 * @return Nette\Database\Table\ActiveRow|false
	 */
	public function findOneBy(array $by)
	{
		return $this->findBy($by)->limit(1)->fetch();
	}


	/**
	 * Vrací záznam s daným primárním klíčem
	 * @param int $id
	 * @return Nette\Database\Table\ActiveRow|false
	 */
	public function find($id)
	{
		return $this->getTable()->get($id);
	}


	/**
	 * Inserts row in a table.
	 * @param array<string, mixed>|\Traversable<string, mixed>|Selection $data [$column => $value]|\Traversable|Selection for INSERT ... SELECT
	 * @return ActiveRow|int|bool Returns ActiveRow or number of affected rows for Selection or table without primary key
	 */
	public function insert(iterable $data)
	{
		return $this->getTable()->insert($data);
	}


	/**
	 * Start transaction
	 */
	public function beginTransaction(): void
	{
		if ($this->inTransaction() == false) {
			$this->dbExplorer->getConnection()->getPdo()->beginTransaction();
		}
	}


	/**
	 * Commit transaction
	 */
	public function commit(): void
	{
		if ($this->inTransaction()) {
			$this->dbExplorer->getConnection()->getPdo()->commit();
		}
	}


	/**
	 * RollBack transaction
	 */
	public function rollBack(): void
	{
		if ($this->inTransaction()) {
			$this->dbExplorer->getConnection()->getPdo()->rollBack();
		}
	}


	/**
	 * Is in transaction
	 */
	public function inTransaction(): bool
	{
		return $this->dbExplorer->getConnection()->getPdo()->inTransaction();
	}
}

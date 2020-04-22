<?php

declare(strict_types=1);

namespace App\Model;

use Nette;

/**
 * Provadi operace nad databazovou tabulkou
 */
abstract class Repository
{
	use Nette\SmartObject;

	/** @var string */
	protected $tableName;

	/** @var Nette\Database\Context */
	private $dbContext;


	/**
	 * @param Nette\Database\Context $sf
	 * @throws \Nette\InvalidStateException
	 */
	public function __construct(Nette\Database\Context $dbContext)
	{
		$this->dbContext = $dbContext;

		if ($this->tableName === null) {
			$class = get_class($this);

			throw new Nette\InvalidStateException("Table name must be defined in $class::\$tableName.");
		}
	}


	/**
	 * Metoda pro vykonani dotazu pro vice zaznamu
	 *
	 * @param string $sStmt - statemant
	 * @return Nette\Database\ResultSet
	 */
	protected function query($sStmt)
	{
		return call_user_func_array([$this->dbContext->getConnection(), 'query'], func_get_args());
	}


	protected function queryFetch()
	{
		return call_user_func_array([$this->dbContext->getConnection(), 'fetch'], func_get_args());
	}


	/**
	 * Vraci celou tabulku z databaze
	 * @return \Nette\Database\Table\Selection
	 */
	public function getTable()
	{
		return $this->dbContext->table($this->tableName);
	}


	/**
	 * Vraci vsechny zaznamy v tabulce
	 * @return \Nette\Database\Table\Selection
	 */
	public function findAll()
	{
		return $this->getTable();
	}


	/**
	 * Vrací vyfiltrované záznamy na základě vstupního pole
	 * (pole array('name' => 'David') se převede na část SQL dotazu WHERE name = 'David')
	 * @param array $by
	 * @return \Nette\Database\Table\Selection
	 */
	public function findBy(array $by)
	{
		return $this->getTable()->where($by);
	}


	/**
	 * To samé jako findBy akorát vrací vždy jen jeden záznam
	 * @param array $by
	 * @return \Nette\Database\Table\ActiveRow|FALSE
	 */
	public function findOneBy(array $by)
	{
		return $this->findBy($by)->limit(1)->fetch();
	}


	/**
	 * Vrací záznam s daným primárním klíčem
	 * @param int $id
	 * @return \Nette\Database\Table\ActiveRow|FALSE
	 */
	public function find($id)
	{
		return $this->getTable()->get($id);
	}


	/**
	 * Pridani zaznamu
	 * @param type $aValues
	 * @return int id záznamu
	 */
	public function insert($aValues)
	{
		return $this->getTable()->insert($aValues);
	}


	/**
	 * Start transaction
	 */
	public function beginTransaction()
	{
		if ($this->inTransaction() == false) {
			$this->dbContext->getConnection()->getPdo()->beginTransaction();
		}
	}


	/**
	 * Commit transaction
	 */
	public function commit()
	{
		if ($this->inTransaction()) {
			$this->dbContext->getConnection()->getPdo()->commit();
		}
	}


	/**
	 * RollBack transaction
	 */
	public function rollBack()
	{
		if ($this->inTransaction()) {
			$this->dbContext->getConnection()->getPdo()->rollBack();
		}
	}


	/**
	 * Is in transaction
	 */
	public function inTransaction()
	{
		return $this->dbContext->getConnection()->getPdo()->inTransaction();
	}
}

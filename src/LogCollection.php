<?php

namespace Anomaly\Module\Logger;

use Anomaly\Module\Logger\Contracts\FilesystemInterface;
use Anomaly\Module\Logger\Exceptions\LogNotFoundException;
use Anomaly\Pagination\LengthAwarePaginator;
use Anomaly\Support\Collection;

/**
 *	Class LogCollection
 *
 *	@link		https://anomaly.ink
 *	@author		Anomaly lab, Inc <support@anomaly.ink>
 *	@author		Bill Li <bill.li@anomaly.ink>
 *	@package	Anomaly\Module\Logger\LogCollection
 */
class LogCollection extends Collection
{
	private $filesystem;

	/**
	 *	LogCollection constructor.
	 *
	 *	@param		array		$items
	 */
	public function __construct($items = [])
	{
		$this->setFilesystem(anomaly(FilesystemInterface::class));

		parent::__construct($items);
		if (empty($items))
		{
			$this->load();
		}
	}

	/**
	 *	Set the filesystem instance.
	 *
	 *	@param		\Anomaly\Module\Logger\Contracts\FilesystemInterface		$filesystem
	 *
	 *	@return		\Anomaly\Module\Logger\LogCollection
	 */
	public function setFilesystem(FilesystemInterface $filesystem)
	{
		$this->filesystem = $filesystem;
		return $this;
	}

	/**
	 *	Load all logs.
	 *
	 *	@return		\Anomaly\Module\Logger\LogCollection
	 */
	private function load()
	{
		foreach( $this->filesystem->dates(true) as $date => $path )
		{
			$this->put($date, Log::make($date, $path, $this->filesystem->read($date)));
		}
		return $this;
	}

	/**
	 *	Get a log.
	 *
	 *	@param		string			$date
	 *	@param		mixed|null		$default
	 *
	 *	@return		\Anomaly\Module\Logger\Log
	 *
	 *	@throws		\Anomaly\Module\Logger\Exceptions\LogNotFoundException
	 */
	public function get($date, $default = null)
	{
		if ( ! $this->has($date) )
		{
			throw new LogNotFoundException("Log not found in this date [$date]");
		}

		return parent::get($date, $default);
	}

	/**
	 *	Paginate logs.
	 *
	 *	@param		int		$perPage
	 *
	 *	@return		\Anomaly\Pagination\LengthAwarePaginator
	 */
	public function paginate(int $perPage = 15)
	{
		$page = request()->get('page', 1);
		$path = request()->url();
		return new LengthAwarePaginator(
			$this->forPage($page, $perPage),
			$this->count(),
			$perPage,
			$page,
			compact('path')
		);
	}

	/**
	 *	Get a log (alias).
	 *
	 *	@see get()
	 *
	 *	@param		string		$date
	 *
	 *	@return		\Anomaly\Module\Logger\Log
	 */
	public function log($date)
	{
		return $this->get($date);
	}

	/**
	 *	Get log entries.
	 *
	 *	@param		string		$date
	 *	@param		string		$level
	 *
	 *	@return		\Anomaly\Module\Logger\LogEntryCollection
	 */
	public function entries($date, $level = 'all')
	{
		return $this->get($date)->entries($level);
	}

	/**
	 *	Get logs statistics.
	 *
	 *	@return		array
	 */
	public function stats()
	{
		$stats = [];
		foreach ( $this->items as $date => $log )
		{
			$stats[$date] = $log->stats();
		}
		return $stats;
	}

	/**
	 *	List the log files (dates).
	 *
	 *	@return		array
	 */
	public function dates()
	{
		return $this->keys()->toArray();
	}

	/**
	 *	Get entries total.
	 *
	 *	@param		string		$level
	 *
	 *	@return		int
	 */
	public function total($level = 'all')
	{
		return (int) $this->sum(function (Log $log) use ($level) {
			return $log->entries($level)->count();
		});
	}

	/**
	 *	Get logs tree.
	 *
	 *	@param		bool		$trans
	 *
	 *	@return		array
	 */
	public function tree($trans = false)
	{
		$tree = [];
		foreach ( $this->items as $date => $log )
		{
			$tree[$date] = $log->tree($trans);
		}
		return $tree;
	}

	/**
	 *	Get logs menu.
	 *
	 *	@param		bool		$trans
	 *
	 *	@return		array
	 */
	public function menu($trans = true)
	{
		$menu = [];
		foreach ( $this->items as $date => $log )
		{
			$menu[$date] = $log->menu($trans);
		}

		return $menu;
	}
}
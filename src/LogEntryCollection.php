<?php

namespace Anomaly\Module\Logger;

use Anomaly\Pagination\LengthAwarePaginator;
use Anomaly\Support\Collection;

/**
 *	Class LogEntryCollection
 *
 *	@link		https://anomaly.ink
 *	@author		Anomaly lab, Inc <support@anomaly.ink>
 *	@author		Bill Li <bill.li@anomaly.ink>
 *	@package	Anomaly\Module\Logger\LogEntryCollection
 */
class LogEntryCollection extends Collection
{
	/**
	 *	Load raw log entries.
	 *
	 *	@param		string		$raw
	 *
	 *	@return		self
	 */
	public function load($raw)
	{
		foreach ( LogParser::parse($raw) as $entry )
		{
			list($level, $header, $stack) = array_values($entry);
			$this->push(new LogEntry($level, $header, $stack));
		}
		return $this;
	}

	/**
	 *	Paginate log entries.
	 *
	 *	@param		int		$perPage
	 *
	 *	@return		\Anomaly\Pagination\LengthAwarePaginator
	 */
	public function paginate($perPage = 15)
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
	 *	Get filtered log entries by level.
	 *
	 *	@param		string		$level
	 *
	 *	@return		self
	 */
	public function filterByLevel($level)
	{
		return $this->filter(function(LogEntry $entry) use ($level) {
			return $entry->isSameLevel($level);
		});
	}

	/**
	 *	Get log entries stats.
	 *
	 *	@return		array
	 */
	public function stats()
	{
		$counters = $this->initStats();
		foreach ($this->groupBy('level') as $level => $entries) {
			$counters[$level] = $count = count($entries);
			$counters['all'] += $count;
		}
		return $counters;
	}

	/**
	 *	Get the log entries navigation tree.
	 *
	 *	@param		bool		$trans
	 *
	 *	@return		array
	 */
	public function tree($trans = false)
	{
		$tree = $this->stats();
		array_walk($tree, function(&$count, $level) use ($trans) {
			$count = [
				'name'	=> $trans ? log_levels()->get($level) : $level,
				'count' => $count,
			];
		});
		return $tree;
	}

	/**
	 *	Init stats counters.
	 *
	 *	@return		array
	 */
	private function initStats()
	{
		$levels = array_merge_recursive(
			['all'],
			array_keys(log_viewer()->levels(true))
		);

		return array_map(function () {
			return 0;
		}, array_flip($levels));
	}
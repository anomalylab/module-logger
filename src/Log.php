<?php

namespace Anomaly\Module\Logger;

use JsonSerializable;
use SplFileInfo;
use Anomaly\Moment\Moment;
use Anomaly\Arrays\Contracts\Arrayable;
use Anomaly\Support\Contracts\Jsonable;

/**
 *	Class Log
 *
 *	@link		https://anomaly.ink
 *	@author		Anomaly lab, Inc <support@anomaly.ink>
 *	@author		Bill Li <bill.li@anomaly.ink>
 *	@package	Anomaly\Module\Logger\Log
 */
class Log implements Arrayable, Jsonable, JsonSerializable
{

	public $date;

	private $path;

	private $entries;

	private $file;

	/**
	 *	Log constructor.
	 *
	 *	@param		string		$date
	 *	@param		string		$path
	 *	@param		string		$raw
	 */
	public function __construct(string $date, string $path, string $raw)
	{
		$this->date	= $date;
		$this->path	= $path;
		$this->file	= new SplFileInfo($path);
		$this->entries = (new LogEntryCollection)->load($raw);
	}

	/**
	 *	Get log path.
	 *
	 *	@return		string
	 */
	public function getPath()
	{
		return $this->path;
	}

	/**
	 *	Get file info.
	 *
	 *	@return		\SplFileInfo
	 */
	public function file()
	{
		return $this->file;
	}

	/**
	 *	Get file size.
	 *
	 *	@return		string
	 */
	public function size()
	{
		return $this->formatSize($this->file->getSize());
	}

	/**
	 *	Get file creation date.
	 *
	 *	@return		\Anomaly\Moment\Moment
	 */
	public function createdAt()
	{
		return Moment::createFromTimestamp($this->file()->getATime());
	}

	/**
	 *	Get file modification date.
	 *
	 *	@return		\Anomaly\Moment\Moment
	 */
	public function updatedAt()
	{
		return Moment::createFromTimestamp($this->file()->getMTime());
	}

	/**
	 *	Make a log object.
	 *
	 *	@param		string		$date
	 *	@param		string		$path
	 *	@param		string		$raw
	 *
	 *	@return		self
	 */
	public static function make($date, $path, $raw)
	{
		return new self($date, $path, $raw);
	}

	/**
	 *	Get log entries.
	 *
	 *	@param		string		$level
	 *
	 *	@return		\Anomaly\Module\Logger\LogEntryCollection
	 */
	public function entries($level = 'all')
	{
		return $level === 'all'
			? $this->entries
			: $this->getByLevel($level);
	}

	/**
	 *	Get filtered log entries by level.
	 *
	 *	@param		string		$level
	 *
	 *	@return		\Anomaly\Module\Logger\LogEntryCollection
	 */
	public function getByLevel($level)
	{
		return $this->entries->filterByLevel($level);
	}

	/**
	 *	Get log stats.
	 *
	 *	@return		array
	 */
	public function stats()
	{
		return $this->entries->stats();
	}

	/**
	 *	Get the log navigation tree.
	 *
	 *	@param		bool		$trans
	 *
	 *	@return		array
	 */
	public function tree($trans = false)
	{
		return $this->entries->tree($trans);
	}

	/**
	 *	Get log entries menu.
	 *
	 *	@param		bool		$trans
	 *
	 *	@return		array
	 */
	public function menu($trans = true)
	{
		return log_menu()->make($this, $trans);
	}

	/**
	 *	Get the log as a plain array.
	 *
	 *	@return		array
	 */
	public function toArray() : array
	{
		return [
			'date'	=> $this->date,
			'path'	=> $this->path,
			'entries' => $this->entries->toArray()
		];
	}

	/**
	 *	Convert the object to its JSON representation.
	 *
	 *	@param		int		$options
	 *
	 *	@return		string
	 */
	public function toJson($options = 0) : string
	{
		return json_encode($this->toArray(), $options);
	}

	/**
	 *	Serialize the log object to json data.
	 *
	 *	@return		array
	 */
	public function jsonSerialize() : array
	{
		return $this->toArray();
	}
	/* -----------------------------------------------------------------
	 |		Other Methods
	 | -----------------------------------------------------------------
	 */
	/**
	 *	Format the file size.
	 *
	 *	@param		int		$bytes
	 *	@param		int		$precision
	 *
	 *	@return		string
	 */
	private function formatSize($bytes, $precision = 2)
	{
		$units = ['B', 'KB', 'MB', 'GB', 'TB'];
		$bytes = max($bytes, 0);
		$pow = floor(($bytes ? log($bytes) : 0) / log(1024));
		$pow = min($pow, count($units) - 1);
		return round($bytes / pow(1024, $pow), $precision).' '.$units[$pow];
	}
}
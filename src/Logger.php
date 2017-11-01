<?php

/**
 * @license    New BSD License
 * @link       https://github.com/nextras/tracy-monolog-adapter
 */

namespace Nextras\TracyMonologAdapter;

use Monolog;
use Throwable;
use Tracy\Helpers;
use Tracy\ILogger;


class Logger implements ILogger
{
	/** @const Tracy priority to Monolog priority mapping */
	const PRIORITY_MAP = [
        ILogger::DEBUG => Monolog\Logger::DEBUG,
        ILogger::INFO => Monolog\Logger::INFO,
        ILogger::WARNING => Monolog\Logger::WARNING,
        ILogger::ERROR => Monolog\Logger::ERROR,
        ILogger::EXCEPTION => Monolog\Logger::CRITICAL,
        ILogger::CRITICAL => Monolog\Logger::CRITICAL,
	];

	/** @var Monolog\Logger */
	protected $monolog;


	public function __construct(Monolog\Logger $monolog)
	{
		$this->monolog = $monolog;
	}


	public function log($message, $priority = ILogger::INFO)
	{
		$context = [
			'at' => Helpers::getSource(),
		];

		if ($message instanceof Throwable) {
			$context['exception'] = $message;
			$message = '';
		}

		$this->monolog->addRecord(
            (in_array($message, self::PRIORITY_MAP)) ? self::PRIORITY_MAP[$priority] : Monolog\Logger::ERROR,
			$message,
			$context
		);
	}
}

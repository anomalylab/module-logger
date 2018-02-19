<?php

namespace Anomaly\Module\Logger;

use Anomaly\Addon\Module\Module;

/**
 *	Class Logger
 *
 *	@link			https://anomaly.ink
 *	@author			Anomaly lab, Inc <support@anomaly.ink>
 *	@author			Bill Li <bill.li@anomaly.ink>
 *	@package		Anomaly\Module\Logger\Logger
 */
class Logger extends Module
{
	/**
	 *	The navigation display flag.
	 *
	 *	@var		bool
	 */
	protected $navigation = true;

	/**
	 *	The addon icon.
	 *
	 *	@var		string
	 */
	protected $icon = 'icon icon-anomaly';

	/**
	 *	The module sections.
	 *
	 *	@var		array
	 */
	protected $sections = [
        'logger' => [
            'buttons' => [
                'new_logger',
            ],
        ],
    ];

}

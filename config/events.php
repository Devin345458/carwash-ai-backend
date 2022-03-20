<?php
/**
 * Created by PhpStorm.
 * User: Devinhollister-graham
 * Date: 7/14/18
 * Time: 8:49 PM
 */

use App\Events\ActivityLogger;
use Cake\Event\EventManager;

EventManager::instance()->on(new ActivityLogger());

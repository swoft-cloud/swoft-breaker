<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace Swoft\Breaker\State;

use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Breaker\Exception\BreakerException;
use Swoole\Timer;

/**
 * Class OpenState
 *
 * @since 2.0
 *
 * @Bean(scope=Bean::PROTOTYPE)
 */
class OpenState extends AbstractState
{
    /**
     * @throws BreakerException
     */
    public function check(): void
    {
        if ($this->breaker->isOpen()) {
            throw new BreakerException('Breaker is opened!');
        }
    }

    /**
     * Exception
     */
    public function exception(): void
    {
        return;
    }

    /**
     * Reset
     */
    public function reset(): void
    {
        $retryTime = $this->breaker->getRetryTime();
        Timer::after($retryTime * 1000, function (): void {
            $this->breaker->moveToHalfOpen();
        });
    }
}

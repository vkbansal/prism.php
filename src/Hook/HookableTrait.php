<?php
namespace VKBansal\Prism\Hook;

/**
 * Hooks Implementation.
 * @package VKBansal\Prism\Hooks\Hooks
 * @version 0.1.0
 * @author Vivek Kumar Bansal <contact@vkbansal.me>
 * @license MIT
 */
trait HookableTrait
{
    /**
     * @var array
     */
    protected $hooks = [];

    /**
     * Add a Hook
     * @param  string        $name
     * @param  \Closure      $callback
     * @param  string|null   $refer
     * @return array
     */
    public function addHook($name, \Closure $callback, $refer = null)
    {
        $callback = $callback->bindTo($this);

        if (isset($this->hooks[$name])) {
            if ($refer !== null) {
                $this->hooks[$name][$refer] = $callback;
            } else {
                $this->hooks[$name][] = $callback;
            }
        } else {
            if ($refer !== null) {
                $this->hooks[$name] = [];
                $this->hooks[$name][$refer] = $callback;
            } else {
                $this->hooks[$name] = [$callback];
            }
        }

        return [$name, $refer];
    }

    /**
     * Run Hook
     * @param  string $name
     * @param  array  $env
     * @return void
     */
    public function runHook($name, array &$env = [])
    {
        if (!isset($this->hooks[$name]) || count($this->hooks[$name]) < 1) {
            return;
        }

        foreach ($this->hooks[$name] as $key => $callback) {
            $callback($env);
        }
    }

    /**
     * Remove Hook description]
     * @param  string      $name
     * @param  string|null $refer
     * @return void
     */
    public function removeHook($name, $refer = null)
    {
        if ($refer === null) {
            unset($this->hooks[$name]);
        } else {
            unset($this->hooks[$name][$refer]);
        }
    }
}
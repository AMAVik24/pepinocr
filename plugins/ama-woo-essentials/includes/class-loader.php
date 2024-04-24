<?php
/**
 * This class makes it easier to group all the hooks in the main file.
 */

namespace AmaWooEssentials\Includes;

class Loader {

	protected $actions;
	protected $filters;

	public function __construct() {
		$this->actions = array();
		$this->filters = array();
	}

	public function add_action( $hook, $component, $callback, $priority = 10, $accepted_args = 1 ) {
		$this->actions = $this->add( $this->actions, $hook, $component, $callback, $priority, $accepted_args );
	}

	public function add_filter( $hook, $component, $callback, $priority = 10, $accepted_args = 1 ) {
		$this->filters = $this->add( $this->filters, $hook, $component, $callback, $priority, $accepted_args );
	}

	private function add( $hooks, $hook, $component, $callback, $priority, $accepted_args ) {
		$hooks[] = array(
			'hook'          => $hook, //The name of the WordPress action or filter being registered.
			'component'     => $component, //A reference to the instance of the object on which the action or filter is defined.
			'callback'      => $callback, //The name of the function/method that should be executed when the action or filter is triggered.
			'priority'      => $priority, //(optional): The priority at which the function should be executed. Default is 10.
			'accepted_args' => $accepted_args //(optional): The number of arguments that should be passed to the callback. Default is 1.
		);

		return $hooks;
	}

	public function run() {
		foreach ( $this->filters as $hook ) {
			add_filter( $hook['hook'], array( $hook['component'], $hook['callback'] ), $hook['priority'], $hook['accepted_args'] );
		}

		foreach ( $this->actions as $hook ) {
			add_action( $hook['hook'], array( $hook['component'], $hook['callback'] ), $hook['priority'], $hook['accepted_args'] );
		}
	}
}
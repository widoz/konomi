<?php

declare(strict_types=1);

use Brain\Monkey\Functions;

global $wp_hooks;

class WP_Hooks_Mock {
    public $actions = [];
    public $filters = [];

    public function add_action($hook_name, $callback, $priority = 10, $accepted_args = 1) {
        $this->actions[$hook_name][] = [
            'callback' => $callback,
            'priority' => $priority,
            'accepted_args' => $accepted_args
        ];
    }

    public function add_filter($hook_name, $callback, $priority = 10, $accepted_args = 1) {
        $this->filters[$hook_name][] = [
            'callback' => $callback,
            'priority' => $priority,
            'accepted_args' => $accepted_args
        ];
    }

    public function do_action($hook_name, ...$args) {
        if (isset($this->actions[$hook_name])) {
            foreach ($this->actions[$hook_name] as $action) {
                call_user_func_array($action['callback'], array_slice($args, 0, $action['accepted_args']));
            }
        }
    }

    public function apply_filters($hook_name, $value, ...$args) {
        if (isset($this->filters[$hook_name])) {
            foreach ($this->filters[$hook_name] as $filter) {
                $value = call_user_func_array($filter['callback'], array_merge([$value], array_slice($args, 0, $filter['accepted_args'] - 1)));
            }
        }
        return $value;
    }
}

$wp_hooks = new WP_Hooks_Mock();

Functions\when('add_action')->alias(function ($hook_name, $callback, $priority = 10, $accepted_args = 1) {
    global $wp_hooks;
    $wp_hooks->add_action($hook_name, $callback, $priority, $accepted_args);
});

Functions\when('add_filter')->alias(function ($hook_name, $callback, $priority = 10, $accepted_args = 1) {
    global $wp_hooks;
    $wp_hooks->add_filter($hook_name, $callback, $priority, $accepted_args);
});

Functions\when('do_action')->alias(function ($hook_name, ...$args) {
    global $wp_hooks;
    $wp_hooks->do_action($hook_name, ...$args);
});

Functions\when('apply_filters')->alias(function ($hook_name, $value, ...$args) {
    global $wp_hooks;
    return $wp_hooks->apply_filters($hook_name, $value, ...$args);
});

Functions\when('remove_action')->alias(function ($hook_name, $callback, $priority = 10) {
    global $wp_hooks;
    if (isset($wp_hooks->actions[$hook_name])) {
        $wp_hooks->actions[$hook_name] = array_filter($wp_hooks->actions[$hook_name], function ($action) use ($callback, $priority) {
            return $action['callback'] !== $callback || $action['priority'] !== $priority;
        });
    }
});

Functions\when('remove_filter')->alias(function ($hook_name, $callback, $priority = 10) {
    global $wp_hooks;
    if (isset($wp_hooks->filters[$hook_name])) {
        $wp_hooks->filters[$hook_name] = array_filter($wp_hooks->filters[$hook_name], function ($filter) use ($callback, $priority) {
            return $filter['callback'] !== $callback || $filter['priority'] !== $priority;
        });
    }
});

Functions\when('has_action')->alias(function ($hook_name, $callback) {
    global $wp_hooks;
    return isset($wp_hooks->actions[$hook_name]) && in_array($callback, array_column($wp_hooks->actions[$hook_name], 'callback'));
});

Functions\when('has_filter')->alias(function ($hook_name, $callback) {
    global $wp_hooks;
    return isset($wp_hooks->filters[$hook_name]) && in_array($callback, array_column($wp_hooks->filters[$hook_name], 'callback'));
});

Functions\when('doing_action')->alias(function ($hook_name) {
    global $wp_hooks;
    return isset($wp_hooks->actions[$hook_name]);
});

Functions\when('doing_filter')->alias(function ($hook_name) {
    global $wp_hooks;
    return isset($wp_hooks->filters[$hook_name]);
});

Functions\when('did_action')->alias(function ($hook_name) {
    global $wp_hooks;
    return isset($wp_hooks->actions[$hook_name]) ? count($wp_hooks->actions[$hook_name]) : 0;
});

Functions\when('did_filter')->alias(function ($hook_name) {
    global $wp_hooks;
    return isset($wp_hooks->filters[$hook_name]) ? count($wp_hooks->filters[$hook_name]) : 0;
});

Functions\when('current_filter')->alias(function () {
    global $wp_hooks;
    return end(array_keys($wp_hooks->filters));
});

Functions\when('current_action')->alias(function () {
    global $wp_hooks;
    return end(array_keys($wp_hooks->actions));
});

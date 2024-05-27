<?php

namespace App\Http;

class Session
{
    /**
     * Starts the session if it has not been started yet.
     */
    public static function start(): void
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }

    /**
     * Stores a value in the session.
     *
     * @param string $key   The key to store the value under.
     * @param mixed  $value The value to store.
     */
    public static function put(string $key, $value): void
    {
        $_SESSION[$key] = $value;
    }

    /**
     * Retrieves a value from the session.
     *
     * @param string $key The key of the value to retrieve.
     *
     * @return mixed|null The value stored under the given key, or null if the key does not exist.
     */
    public static function get(string $key)
    {
        return $_SESSION[$key] ?? null;
    }

    /**
     * Checks if a value exists in the session.
     *
     * @param string $key The key to check.
     *
     * @return bool True if the key exists, false otherwise.
     */
    public static function has(string $key): bool
    {
        return isset($_SESSION[$key]);
    }

    /**
     * Removes a value from the session.
     *
     * @param string $key The key of the value to remove.
     */
    public static function remove(string $key): void
    {
        unset($_SESSION[$key]);
    }

    /**
     * Destroys the session and clears all session data.
     */
    public static function destroy(): void
    {
        session_destroy();
    }
}

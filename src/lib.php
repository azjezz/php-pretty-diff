<?php

namespace PrettyDiff;

use FFI;
use function print_r;

/**
 * The path to the generated C header that declares the Rust functions.
 * Adjust this path as needed for your setup.
 */
const HEADER_FILE = __DIR__ . '/lib.h';

/**
 * PrettyDiff provides static methods that wrap the underlying Rust functions.
 */
class PrettyDiff
{
    private static ?FFI $instance = null;

    /**
     * Call the diff_chars function from Rust.
     *
     * @param mixed $expected              The "expected" value.
     * @param mixed $actual                The "actual" value.
     * @param bool   $highlightWhitespace   Whether to highlight whitespace differences.
     * @return string The diff result as a PHP string.
     */
    public static function diffChars(mixed $expected, mixed $actual, bool $highlightWhitespace): string
    {
        $expected = print_r($expected, true);
        $actual = print_r($actual, true);

        $ffi       = static::getInstance();
        $cExpected = self::toCString($expected);
        $cActual   = self::toCString($actual);

        return $ffi->diff_chars($cExpected, $cActual, $highlightWhitespace);
    }

    /**
     * Call the diff_words function from Rust.
     *
     * @param mixed $expected              The "expected" value.
     * @param mixed $actual                The "actual" value.
     * @param bool   $highlightWhitespace   Whether to highlight whitespace differences.
     * @return string The diff result as a PHP string.
     */
    public static function diffWords(mixed $expected, mixed $actual, bool $highlightWhitespace): string
    {
        $expected = print_r($expected, true);
        $actual = print_r($actual, true);

        $ffi       = static::getInstance();
        $cExpected = self::toCString($expected);
        $cActual   = self::toCString($actual);

        return $ffi->diff_words($cExpected, $cActual, $highlightWhitespace);
    }

    /**
     * Call the diff_lines function from Rust.
     *
     * @param mixed $expected             The "expected" value.
     * @param mixed $actual               The "actual" value.
     * @param bool   $displayLineNumbers   Whether to display line numbers in the diff.
     * @return string The diff result as a PHP string.
     */
    public static function diffLines(mixed $expected, mixed $actual, bool $displayLineNumbers): string
    {
        $expected = print_r($expected, true);
        $actual = print_r($actual, true);

        $ffi       = static::getInstance();
        $cExpected = self::toCString($expected);
        $cActual   = self::toCString($actual);

        return $ffi->diff_lines($cExpected, $cActual, $displayLineNumbers);
    }

    /**
     * Return the singleton FFI instance, creating it if necessary.
     *
     * @return FFI
     */
    private static function getInstance(): FFI {
        if (self::$instance === null) {
            $libPath = self::getLibraryPath();

            // If the Rust library is not found, throw an error
            if (!file_exists($libPath)) {
                throw new RuntimeException(
                    "Cannot load Rust library. The file '{$libPath}' does not exist. " .
                    "Please ensure you have built the library for your platform."
                );
            }

            // Load the C header and the Rust library
            self::$instance = FFI::cdef(
                file_get_contents(HEADER_FILE),
                $libPath
            );
        }

        return self::$instance;
    }

    /**
     * Convert a PHP string to a C string buffer using FFI.
     *
     * @param string $input The PHP string to convert.
     * @return FFI\CData    A C buffer containing the null-terminated string.
     */
    private static function toCString(string $input): FFI\CData
    {
        $ffi = static::getInstance();
        // Allocate enough bytes for the string plus the null terminator
        $cstr = $ffi->new("char[" . (strlen($input) + 1) . "]");
        // Copy the PHP string into the allocated buffer
        FFI::memcpy($cstr, $input, strlen($input));
        // Add the null terminator
        $cstr[strlen($input)] = "\0";
        return $cstr;
    }
    
    /**
     * Dynamically determine the path to the built Rust library based on the OS.
     *
     * @return string
     */
    private static function getLibraryPath(): string
    {
        return match (strtolower(PHP_OS_FAMILY)) {
            'darwin' => __DIR__ . '/../target/release/libphp_pretty_diff.dylib',
            'linux' => __DIR__ . '/../target/release/libphp_pretty_diff.so',
            'windows' => __DIR__ . '/../target/release/php_pretty_diff.dll',
            default => throw new RuntimeException(
                'Unsupported OS for php_pretty_diff: ' . PHP_OS_FAMILY
            )
        };
    }
}

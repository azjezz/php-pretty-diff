# PHP Pretty Diff: Rust + FFI Example

This repository demonstrates how to use Rust code in a PHP project via [PHP FFI (Foreign Function Interface)](https://www.php.net/manual/en/book.ffi.php).
It is not intended as a production library or for general distribution, but rather as a simple example showing the necessary steps.

## Project Overview

- [`src/lib.rs`](src/lib.rs): Rust source code that provides three diff functions:

  - `diff_chars`: Compare two strings at the character level.
  - `diff_words`: Compare two strings at the word level.
  - `diff_lines`: Compare two strings line-by-line.

  The Rust code uses the [prettydiff](https://crates.io/crates/prettydiff) crate.

- [`src/lib.h`](src/lib.h): C header file describing the exported Rust functions.
- [`src/lib.php`](src/lib.php): PHP class that loads the Rust library using FFI and provides PHP-friendly methods.

## Requirements

- PHP 7.4 or later:
  - PHP built with FFI support ( using `--with-ffi` flag).
  - In `php.ini`, you may need to enable FFI. For example:
    ```ini
    ffi.enable=true
    ```
- Rust Toolchain (e.g., via [rustup](https://rustup.rs/))

## Building

1. Clone the repository:

   ```bash
   git clone git@github.com:azjezz/php-pretty-diff.git
   cd php-pretty-diff
   ```

2. Build the Rust library:

   ```bash
   cargo build --release
   ```

   This should create a shared library file in `target/release/libprettydiff.so` (or `libprettydiff.dylib` on macOS or `libprettydiff.dll` on Windows).

## Running the Example

You can run the example by executing the PHP script:

```bash
php examples/use.php
```

This script demonstrates how to use the Rust library from PHP.

## How It Works

1. Rust:

`src/lib.rs` declares `pub extern "C"` functions that accept C-compatible pointer types (*const i8 / \*const c_char) and return a pointer to a UTF-8 string (*const u8).

Rust strings are converted from those pointers with `CStr`, processed, and then the result is returned.

2. C Header:

`src/lib.h` declares the functions that are exported from the Rust library, for example:

```c
const char* diff_chars(const char* expected, const char* actual, bool highlight_whitespaces);
```

This header is essential for PHP FFI to understand the Rust function signatures.

3. PHP:

`src/lib.php` uses PHP FFI to load the Rust library and define PHP-friendly methods that call the Rust functions.

## Customization

- Add more functions:

Implement additional functions in Rust ( `src/lib.rs`), and remember to expose them via `pub extern "C"`.

- Update the header file:

If you add new functions, update `src/lib.h` to declare them.

- Update the PHP class:

Add new methods to `src/lib.php` that call the new Rust functions.

## Troubleshooting

- Missing library:
  If PHP complains it can’t find `libphp_pretty_diff.so` (or `.dylib`, `.dll`), verify:

  1. You actually built the Rust library successfully (`cargo build --release`).
  2. The file name matches what your platform expects (check `getLibraryPath()` in `src/lib.php`).

- Undefined symbol:
  Ensure the function signature in `src/lib.h` matches exactly what’s in `src/lib.rs`, and
  that your Rust functions are declared as `pub extern "C"` and uses the `#[unsafe(no_mangle)]` attribute.

- FFI not enabled:
  Make sure your `php.ini` enables the ffi extension. Otherwise, PHP 7.4 or 8+ won’t load it by default.

## Disclaimer

This is a demonstration project. It’s not production-ready, nor is it intended for actual distribution.
Use it as a learning reference for calling Rust code from PHP using FFI.

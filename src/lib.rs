use std::ffi::CStr;
use std::ffi::CString;
use std::ffi::c_char;

use prettydiff::diff_chars as rust_diff_chars;
use prettydiff::diff_lines as rust_diff_lines;
use prettydiff::diff_words as rust_diff_words;

#[unsafe(no_mangle)]
pub extern "C" fn diff_chars(
    expected: *const c_char,
    actual: *const c_char,
    highlight_whitespaces: bool,
) -> *const c_char {
    let expected = unsafe { CStr::from_ptr(expected) };
    let actual = unsafe { CStr::from_ptr(actual) };

    let expected = expected.to_str().unwrap();
    let actual = actual.to_str().unwrap();

    let diff = rust_diff_chars(expected, actual)
        .set_highlight_whitespace(highlight_whitespaces)
        .format();

    CString::new(diff).unwrap().into_raw()
}

#[unsafe(no_mangle)]
pub extern "C" fn diff_words(
    expected: *const c_char,
    actual: *const c_char,
    highlight_whitespaces: bool,
) -> *const c_char {
    let expected = unsafe { CStr::from_ptr(expected) };
    let actual = unsafe { CStr::from_ptr(actual) };

    let expected = expected.to_str().unwrap();
    let actual = actual.to_str().unwrap();

    let diff = rust_diff_words(expected, actual)
        .set_highlight_whitespace(highlight_whitespaces)
        .format();

    CString::new(diff).unwrap().into_raw()
}

#[unsafe(no_mangle)]
pub extern "C" fn diff_lines(
    expected: *const c_char,
    actual: *const c_char,
    display_line_numbers: bool,
) -> *const c_char {
    let expected = unsafe { CStr::from_ptr(expected) };
    let actual = unsafe { CStr::from_ptr(actual) };

    let expected = expected.to_str().unwrap();
    let actual = actual.to_str().unwrap();

    let diff = rust_diff_lines(expected, actual).format_with_context(None, display_line_numbers);

    CString::new(diff).unwrap().into_raw()
}

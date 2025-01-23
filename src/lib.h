#include <stdbool.h>

/**
 * Compare two UTF-8 strings by characters and return a formatted diff as a UTF-8 C string.
 *
 * @param expected Pointer to null-terminated UTF-8 string for "expected" text.
 * @param actual Pointer to null-terminated UTF-8 string for "actual" text.
 * @param highlight_whitespaces Boolean to control highlighting whitespace differences.
 * @return Pointer to a null-terminated UTF-8 C string containing the diff.
 */
const char* diff_chars(const char* expected, const char* actual, bool highlight_whitespaces);

/**
 * Compare two UTF-8 strings by words and return a formatted diff as a UTF-8 C string.
 *
 * @param expected Pointer to null-terminated UTF-8 string for "expected" text.
 * @param actual Pointer to null-terminated UTF-8 string for "actual" text.
 * @param highlight_whitespaces Boolean to control highlighting whitespace differences.
 * @return Pointer to a null-terminated UTF-8 C string containing the diff.
 */
const char* diff_words(const char* expected, const char* actual, bool highlight_whitespaces);

/**
 * Compare two UTF-8 strings by lines and return a formatted diff as a UTF-8 C string.
 *
 * @param expected Pointer to null-terminated UTF-8 string for "expected" text.
 * @param actual Pointer to null-terminated UTF-8 string for "actual" text.
 * @param display_line_numbers Boolean to indicate whether to display line numbers in the diff.
 * @return Pointer to a null-terminated UTF-8 C string containing the diff.
 */
const char* diff_lines(const char* expected, const char* actual, bool display_line_numbers);

/* Essentials YOOtheme Pro 2.4.12 build 1202.1125; ZOOlanders https://www.zoolanders.com; Copyright (C) Joolanders, SL; http://www.gnu.org/licenses/gpl.html GNU/GPL */

/**
 * Validates the syntax of a template string.
 * The syntax is valid when:
 * - Every opening tag {{ calc.id }} has a matching closing tag {{ /calc }}
 * - Tags are properly nested
 * - All tags from the same conditional group (opening and closing) must be on the same line
 *
 * @param {string} template - The template string to validate
 * @returns {boolean} - True if the syntax is valid, false otherwise
 */
export default function validateTemplateSyntax(template) {
    // First, check if all tags in a conditional block are on the same line
    // This is a simpler check we can do before the full validation
    const calcOpenRegex = /{{ calc\.(\w{4}) }}/g;
    const calcCloseRegex = /{{ \/calc }}/g;

    // Find all opening tags and track their IDs
    const openTags = [...template.matchAll(calcOpenRegex)];
    const closeTags = [...template.matchAll(calcCloseRegex)];

    // Check if tags are on different lines
    const lines = template.split('\n');
    const getLineNumber = (index) => {
        let charCount = 0;
        for (let i = 0; i < lines.length; i++) {
            charCount += lines[i].length + 1; // +1 for newline
            if (charCount > index) return i;
        }
        return lines.length - 1;
    };

    // Create a map of opening tags with their line numbers
    const openTagsMap = new Map();
    for (const match of openTags) {
        const lineNum = getLineNumber(match.index);
        openTagsMap.set(match.index, { line: lineNum, id: match[1] });
    }

    // Check if closing tags are on the same line as their opening tag
    for (const match of closeTags) {
        // Find the closest opening tag before this closing tag
        let closestOpenTag = null;
        let maxIndex = -1;

        for (const [index, tag] of openTagsMap.entries()) {
            if (index < match.index && index > maxIndex) {
                closestOpenTag = tag;
                maxIndex = index;
            }
        }

        if (closestOpenTag && getLineNumber(match.index) !== closestOpenTag.line) {
            return false; // Closing tag on different line than opening tag
        }
    }

    // Now do the full validation for proper nesting and matching tags
    const tagRegex = /{{ (calc\.(\w{4})|\/calc) }}/g;
    const allTags = [...template.matchAll(tagRegex)];
    const stack = [];

    // Track if we're inside a conditional block to detect nesting
    let insideConditional = false;

    for (const match of allTags) {
        const [, tagType] = match;

        if (tagType.startsWith('calc.')) {
            // Opening tag
            if (insideConditional) {
                return false; // Nested if tags are not allowed
            }
            insideConditional = true;
            stack.push({ type: 'calc' });
        } else if (tagType === '/calc') {
            // Closing tag
            if (stack.length === 0) {
                return false; // Closing tag without matching opening tag
            }

            insideConditional = false;
            stack.pop();
        }
    }

    // If the stack is empty, all tags are properly matched
    return stack.length === 0;
}

/**
 * Run tests for the template syntax validator
 * @returns {Object} - Test results with counts of passed and failed tests
 */
export function test() {
    const tests = [
        // Valid examples
        { name: 'Calc', template: '{{ calc.x34y }}{{ /calc }}', expected: true },
        { name: 'Calc with content', template: '{{ calc.x34y }}2*2{{ /calc }}', expected: true },
        {
            name: 'Multiple on same line',
            template:
                '{{ calc.x36y }}a{{ /calc }}{{ calc.x37y }}b{{ /calc }}{{ calc.x38y }}c{{ else }}d{{ /calc }}{{ calc.x39y }}e{{ else }}f{{ /calc }}',
            expected: true
        },
        {
            name: 'With nested source',
            template: '{{ calc.x36y }}{{ sources.x38y }}{{ /calc }}',
            expected: true
        },

        // Invalid examples
        { name: 'Calc missing end tag', template: '{{ calc.x35y }}', expected: false },
        { name: 'Calc missing open tag', template: '{{ /calc }}', expected: false },
        {
            name: 'Nested Calc',
            template: '{{ calc.x35y }}{{ calc.x35y }}{{ /calc }}{{ /calc }}',
            expected: false
        },
        {
            name: 'Tags on different lines',
            template: '{{ calc.x35y }}2*1\n{{ /calc }}',
            expected: false
        }
    ];

    const results = { passed: 0, failed: 0, details: [] };

    tests.forEach((test) => {
        const result = validateTemplateSyntax(test.template);
        const passed = result === test.expected;

        if (passed) {
            results.passed++;
        } else {
            results.failed++;
        }

        results.details.push({
            name: test.name,
            passed,
            expected: test.expected,
            actual: result
        });

        console.log(
            `${passed ? 'PASSED' : 'FAILED'} - ${test.name} (expected: ${test.expected}, got: ${result})`
        );
    });

    console.log(`\nSummary: ${results.passed} passed, ${results.failed} failed`);
    return results;
}

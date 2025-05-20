import {describe, it, expect} from '@jest/globals';
import {parseHTML} from '@test/helpers'
import { getByTestId } from '@testing-library/dom';
import { findInteractivityParent } from '../../../../../../sources/Blocks/Konomi/view/utils';

const MARKUP_ROOT_IS_INTERACTIVE = `
	<div data-testid="parent" data-wp-interactive="konomi">
		<span data-testid="element">Interactive</span>
	</div>
`

const MARKUP_ROOT_WRONG_INTERACTIVE_NAME = `
	<div data-wp-interactive="not-konomi">
		<span data-testid="element">Not interactive</span>
	</div>
`

const MARKUP_ROOT_NOT_INTERACTIVE = `
	<div>
		<span data-testid="element">Not interactive</span>
	</div>
`

const MARKUP_DEEP_NESTED_HAS_INTERACTIVE_PARENT = `
	<div data-wp-interactive="konomi">
		<div data-wp-interactive="not-konomi">
			<div class="not-interactive">
				<span data-testid="element">Interactive</span>
			</div>
		</div>
	</div>
`

describe('Utils', () => {
	describe('findInteractivityParent', () => {
		it('should return the parent if it is the same of the given element', () => {
			const dom = parseHTML(MARKUP_ROOT_IS_INTERACTIVE);
			expect(findInteractivityParent(dom)).toEqual(dom);
		})

		it('should return the parent element when it exists', () => {
			const dom = parseHTML(MARKUP_ROOT_IS_INTERACTIVE);
			const element = getByTestId(dom, 'element');
			expect(findInteractivityParent(element)).not.toEqual(null);
		});

		it('should return the parent element when it is deeply nested', () => {
			const dom = parseHTML(MARKUP_DEEP_NESTED_HAS_INTERACTIVE_PARENT);
			const element = getByTestId(dom, 'element');
			expect(findInteractivityParent(element)).not.toEqual(null);
		});

		it('should return null when the parent element does not exist', () => {
			const dom = parseHTML(MARKUP_ROOT_NOT_INTERACTIVE);
			const element = getByTestId(dom, 'element');
			expect(findInteractivityParent(element)).toEqual(null);
		});

		it('should return null when the parent element has the wrong interactive name', () => {
			const dom = parseHTML(MARKUP_ROOT_WRONG_INTERACTIVE_NAME);
			const element = getByTestId(dom, 'element');
			expect(findInteractivityParent(element)).toEqual(null);
		});
	});
})

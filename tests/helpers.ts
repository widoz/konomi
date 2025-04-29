export function parseHTML(html: string): HTMLElement {
	const parser = new DOMParser();
	const doc = parser.parseFromString(html, 'text/html');
	return doc.body.firstElementChild as HTMLElement;
}

// Inline SVG icons to avoid extra requests
const ICONS = {
	close: '<svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" class="moments-icon moments-icon-close"><path d="M10.5859 12L2.79297 4.20706L4.20718 2.79285L12.0001 10.5857L19.793 2.79285L21.2072 4.20706L13.4143 12L21.2072 19.7928L19.793 21.2071L12.0001 13.4142L4.20718 21.2071L2.79297 19.7928L10.5859 12Z" fill="currentColor"/></svg>',
	prev: '<svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" class="moments-icon moments-icon-prev"><path d="M7.82843 10.9999H20V12.9999H7.82843L13.1924 18.3638L11.7782 19.778L4 11.9999L11.7782 4.22168L13.1924 5.63589L7.82843 10.9999Z" fill="currentColor"/></svg>',
	next: '<svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" class="moments-icon moments-icon-next"><path d="M16.1716 10.9999L10.8076 5.63589L12.2218 4.22168L20 11.9999L12.2218 19.778L10.8076 18.3638L16.1716 12.9999H4V10.9999H16.1716Z" fill="currentColor"/></svg>',
	clock: '<svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" class="moments-icon moments-icon-clock"><path d="M12 22C6.47715 22 2 17.5228 2 12C2 6.47715 6.47715 2 12 2C17.5228 2 22 6.47715 22 12C22 17.5228 17.5228 22 12 22ZM12 20C16.4183 20 20 16.4183 20 12C20 7.58172 16.4183 4 12 4C7.58172 4 4 7.58172 4 12C4 16.4183 7.58172 20 12 20ZM13 12H17V14H11V7H13V12Z" fill="currentColor"/></svg>',
};

interface MomentImage {
	src: string;
	srcset: string;
	webpSrcset?: string | null;
	alt: string;
	width: number;
	height: number;
	sizes: string;
}

interface MomentDate {
	timestamp: string;
	formatted: string;
}

interface MomentData {
	url: string;
	image: MomentImage | null;
	text?: string | null;
	date?: MomentDate | null;
	prev?: string | null;
	next?: string | null;
}

export class MomentsLightbox extends HTMLElement {
	private dialog: HTMLDialogElement | null = null;
	private placeholder: HTMLElement | null = null;
	private closeUrl: string = '';
	private currentMoment: MomentData | null = null;
	private momentDataMap: Map<string, MomentData> = new Map();
	private lastFocusedElement: HTMLElement | null = null;
	private boundPopstateHandler: (e: PopStateEvent) => void;
	private boundKeydownHandler: (e: KeyboardEvent) => void;

	constructor() {
		super();
		this.boundPopstateHandler = this.handlePopstate.bind(this);
		this.boundKeydownHandler = this.handleKeydown.bind(this);
	}

	connectedCallback() {
		this.closeUrl = this.getAttribute('close-url') || '/';
		this.dialog = this.querySelector('dialog.moments-lightbox');
		this.placeholder = this.querySelector('[data-placeholder]');

		// Build moment data map from grid links
		this.querySelectorAll<HTMLAnchorElement>('a[data-moment]').forEach(link => {
			const data = link.getAttribute('data-moment');
			if (data) {
				try {
					const momentData = JSON.parse(data) as MomentData;
					this.momentDataMap.set(momentData.url, momentData);
				} catch (e) {
					console.error('Failed to parse moment data:', e);
				}
			}
		});

		// Add click listener for grid links
		this.addEventListener('click', this.handleClick.bind(this));

		// Add popstate listener for browser navigation
		window.addEventListener('popstate', this.boundPopstateHandler);

		// Check if we should open a moment based on current URL
		this.checkUrlForMoment();
	}

	disconnectedCallback() {
		window.removeEventListener('popstate', this.boundPopstateHandler);
	}

	private handleClick(e: MouseEvent) {
		const target = e.target as HTMLElement;

		// Handle close button click
		const closeLink = target.closest<HTMLAnchorElement>('.moments-close');
		if (closeLink) {
			e.preventDefault();
			this.closeLightbox(true);
			return;
		}

		// Handle moment link click
		const link = target.closest<HTMLAnchorElement>('a[data-moment]');
		if (link) {
			e.preventDefault();
			const data = link.getAttribute('data-moment');
			if (data) {
				try {
					const momentData = JSON.parse(data) as MomentData;
					this.lastFocusedElement = link;
					this.openMoment(momentData, true);
				} catch (e) {
					console.error('Failed to parse moment data:', e);
				}
			}
		}
	}

	private checkUrlForMoment() {
		const currentUrl = window.location.pathname;
		// Check if current URL matches any moment
		for (const [url, data] of this.momentDataMap) {
			const momentPath = new URL(url, window.location.origin).pathname;
			if (currentUrl === momentPath) {
				this.openMoment(data, false);
				return;
			}
		}
	}

	private handlePopstate(_e: PopStateEvent) {
		const currentUrl = window.location.pathname;

		// Check if we should show a moment
		for (const [url, data] of this.momentDataMap) {
			const momentPath = new URL(url, window.location.origin).pathname;
			if (currentUrl === momentPath) {
				this.openMoment(data, false);
				return;
			}
		}

		// Otherwise close the lightbox
		this.closeLightbox(false);
	}

	private openMoment(data: MomentData, pushState: boolean) {
		if (!this.dialog || !this.placeholder) return;

		this.currentMoment = data;

		// Render content
		this.renderMoment(data);

		// Show dialog
		this.dialog.showModal();

		// Update URL
		if (pushState) {
			history.pushState({ moment: data.url }, '', data.url);
		}

		// Focus close button
		const closeButton = this.dialog.querySelector<HTMLAnchorElement>('.moments-close');
		closeButton?.focus();

		// Add keyboard listener
		this.dialog.addEventListener('keydown', this.boundKeydownHandler);

		// Preload adjacent images
		this.preloadAdjacentImages(data);
	}

	private renderMoment(data: MomentData) {
		if (!this.placeholder) return;

		let html = '';

		// Image
		if (data.image) {
			html += `<figure class="moments-image"><picture>`;
			if (data.image.webpSrcset) {
				html += `<source srcset="${data.image.webpSrcset}" sizes="${data.image.sizes}" type="image/webp">`;
			}
			html += `<img alt="${this.escapeHtml(data.image.alt)}" src="${data.image.src}" srcset="${data.image.srcset}" sizes="${data.image.sizes}" width="${data.image.width}" height="${data.image.height}">`;
			html += `</picture></figure>`;

			// Footer
			html += `<div class="moments-image-footer">`;
			if (data.text) {
				html += `<div class="moments-image-footer__text"><p>${this.escapeHtml(data.text)}</p></div>`;
			}
			if (data.date) {
				html += `<moments-time class="moments-image-footer__time">${ICONS.clock}<time datetime="${data.date.timestamp}">${data.date.formatted}</time></moments-time>`;
			}
			html += `</div>`;
		}

		// Controls
		html += `<div class="moments-controls">`;
		if (data.prev) {
			html += `<a href="${data.prev}" class="moments-controls__prev" rel="nofollow" data-nav="prev">${ICONS.prev}</a>`;
		}
		if (data.next) {
			html += `<a href="${data.next}" class="moments-controls__next" rel="nofollow" data-nav="next">${ICONS.next}</a>`;
		}
		html += `</div>`;

		this.placeholder.innerHTML = html;

		// Add click handlers for navigation
		this.placeholder.querySelectorAll<HTMLAnchorElement>('[data-nav]').forEach(navLink => {
			navLink.addEventListener('click', (e) => {
				e.preventDefault();
				const href = navLink.getAttribute('href');
				if (href) {
					const nextData = this.momentDataMap.get(href);
					if (nextData) {
						this.openMoment(nextData, true);
					}
				}
			});
		});
	}

	private closeLightbox(pushState: boolean) {
		if (!this.dialog) return;

		this.dialog.close();
		this.currentMoment = null;

		// Remove keyboard listener
		this.dialog.removeEventListener('keydown', this.boundKeydownHandler);

		// Update URL
		if (pushState) {
			history.pushState(null, '', this.closeUrl);
		}

		// Return focus to last focused element
		this.lastFocusedElement?.focus();
	}

	private handleKeydown(e: KeyboardEvent) {
		if (!this.dialog || !this.currentMoment) return;

		switch (e.code) {
			case 'ArrowLeft':
				if (this.currentMoment.prev) {
					const prevData = this.momentDataMap.get(this.currentMoment.prev);
					if (prevData) {
						e.preventDefault();
						this.openMoment(prevData, true);
					}
				}
				break;
			case 'ArrowRight':
				if (this.currentMoment.next) {
					const nextData = this.momentDataMap.get(this.currentMoment.next);
					if (nextData) {
						e.preventDefault();
						this.openMoment(nextData, true);
					}
				}
				break;
			case 'Escape':
				e.preventDefault();
				this.closeLightbox(true);
				break;
			case 'Tab':
				this.handleTabKey(e);
				break;
		}
	}

	private handleTabKey(e: KeyboardEvent) {
		if (!this.dialog) return;

		const focusableSelectors = 'a[href], button:not([disabled]), [tabindex]:not([tabindex="-1"])';
		const focusableElements = Array.from(this.dialog.querySelectorAll<HTMLElement>(focusableSelectors));

		if (focusableElements.length === 0) return;

		const firstElement = focusableElements[0];
		const lastElement = focusableElements[focusableElements.length - 1];

		if (e.shiftKey) {
			// Shift + Tab: if on first element, wrap to last
			if (document.activeElement === firstElement) {
				e.preventDefault();
				lastElement.focus();
			}
		} else {
			// Tab: if on last element, wrap to first
			if (document.activeElement === lastElement) {
				e.preventDefault();
				firstElement.focus();
			}
		}
	}

	private preloadAdjacentImages(data: MomentData) {
		const toPreload: string[] = [];

		if (data.prev) {
			const prevData = this.momentDataMap.get(data.prev);
			if (prevData?.image?.src) {
				toPreload.push(prevData.image.src);
			}
		}

		if (data.next) {
			const nextData = this.momentDataMap.get(data.next);
			if (nextData?.image?.src) {
				toPreload.push(nextData.image.src);
			}
		}

		toPreload.forEach(src => {
			const img = new Image();
			img.src = src;
		});
	}

	private escapeHtml(text: string): string {
		const div = document.createElement('div');
		div.textContent = text;
		return div.innerHTML;
	}
}

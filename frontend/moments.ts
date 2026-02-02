class MomentDate {
	private lang: string;

	constructor() {
		this.lang = document.documentElement.lang || 'en'; // Get lang attribute or default to 'en'
		const timestamps: NodeListOf<HTMLElement> = document.querySelectorAll('.moment-time');
		timestamps.forEach(timestamp => this.init(timestamp));
	}

	private init(element: HTMLElement) {
		const time = element.getAttribute('datetime');
		if (!time) return
		const formattedTime = this.formatRelativeDate(time);
		if (formattedTime) element.innerHTML = formattedTime;
	}

	private formatRelativeDate(datestring: string) {
		const now = Date.now();
		const date = new Date(datestring).getTime();
		if (isNaN(date)) return;

		const diff = now - date;
		const days = Math.floor(diff / 86400000);

		if (days >= 3) return;

		const rtf = new Intl.RelativeTimeFormat(this.lang, { numeric: 'auto' });
		if (days < 1) {
			const hours = Math.floor(diff / 3600000);
			if (hours >= 1) return rtf.format(-hours, 'hour');

			const minutes = Math.floor(diff / 60000);
			if (minutes >= 1) return rtf.format(-minutes, 'minute');

			const seconds = Math.floor(diff / 1000);
			return rtf.format(-seconds, 'second');
		}

		return rtf.format(-days, 'day');
	}
}


class MomentLightbox {
	lightbox: HTMLDialogElement | null
	nextLink: string = ""
	prevLink: string = ""
	closeLink: string = ""

	constructor() {
		this.lightbox = document.querySelector('.moment-lightbox');
		this.init()
	}
	init() {
		if (!this.lightbox) return
		this.lightbox.showModal();
		this.nextLink = this.lightbox.querySelector('.moment-controls__next')?.getAttribute('href') ?? "";
		this.prevLink = this.lightbox.querySelector('.moment-controls__prev')?.getAttribute('href') ?? "";
		this.closeLink = this.lightbox.querySelector('.moment-close')?.getAttribute('href') ?? "";
		this.addEventListeners()
	}
	addEventListeners() {
		if (!this.lightbox) return
		this.lightbox.addEventListener('keydown', (e: KeyboardEvent) => {
			switch (e.code) {
				case "ArrowLeft":
					if (this.prevLink) this.goTo(this.prevLink)
					break;
				case "ArrowRight":
					if (this.nextLink) this.goTo(this.nextLink)
					break;
				case "Escape":
					if (this.closeLink) this.goTo(this.closeLink)
					break;
			}
		})
	}
	goTo(url: string) {
		window.location.href = url;
	}
}

document.addEventListener('DOMContentLoaded', () => {
	new MomentDate();
	new MomentLightbox();
});

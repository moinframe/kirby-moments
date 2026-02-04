export class MomentsTime extends HTMLElement {
	connectedCallback() {
		const time = this.querySelector('time');
		if (!time) return;

		const datetime = time.getAttribute('datetime');
		if (!datetime) return;

		const formatted = this.formatRelativeDate(datetime);
		if (formatted) {
			time.textContent = formatted;
		}
	}

	private formatRelativeDate(datestring: string): string | undefined {
		const lang = document.documentElement.lang || 'en';
		const now = Date.now();
		const date = new Date(datestring).getTime();
		if (isNaN(date)) return;

		const diff = now - date;
		const days = Math.floor(diff / 86400000);

		if (days >= 3) return;

		const rtf = new Intl.RelativeTimeFormat(lang, { numeric: 'auto' });
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

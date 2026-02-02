"use strict";
class MomentDate {
    constructor() {
        Object.defineProperty(this, "lang", {
            enumerable: true,
            configurable: true,
            writable: true,
            value: void 0
        });
        this.lang = document.documentElement.lang || 'en'; // Get lang attribute or default to 'en'
        const timestamps = document.querySelectorAll('.moment-time');
        timestamps.forEach(timestamp => this.init(timestamp));
    }
    init(element) {
        const time = element.getAttribute('datetime');
        if (!time)
            return;
        const formattedTime = this.formatRelativeDate(time);
        if (formattedTime)
            element.innerHTML = formattedTime;
    }
    formatRelativeDate(datestring) {
        const now = Date.now();
        const date = new Date(datestring).getTime();
        if (isNaN(date))
            return;
        const diff = now - date;
        const days = Math.floor(diff / 86400000);
        if (days >= 3)
            return;
        const rtf = new Intl.RelativeTimeFormat(this.lang, { numeric: 'auto' });
        if (days < 1) {
            const hours = Math.floor(diff / 3600000);
            if (hours >= 1)
                return rtf.format(-hours, 'hour');
            const minutes = Math.floor(diff / 60000);
            if (minutes >= 1)
                return rtf.format(-minutes, 'minute');
            const seconds = Math.floor(diff / 1000);
            return rtf.format(-seconds, 'second');
        }
        return rtf.format(-days, 'day');
    }
}
class MomentLightbox {
    constructor() {
        Object.defineProperty(this, "lightbox", {
            enumerable: true,
            configurable: true,
            writable: true,
            value: void 0
        });
        Object.defineProperty(this, "nextLink", {
            enumerable: true,
            configurable: true,
            writable: true,
            value: ""
        });
        Object.defineProperty(this, "prevLink", {
            enumerable: true,
            configurable: true,
            writable: true,
            value: ""
        });
        Object.defineProperty(this, "closeLink", {
            enumerable: true,
            configurable: true,
            writable: true,
            value: ""
        });
        this.lightbox = document.querySelector('.moment-lightbox');
        this.init();
    }
    init() {
        var _a, _b, _c, _d, _e, _f;
        if (!this.lightbox)
            return;
        this.lightbox.showModal();
        this.nextLink = (_b = (_a = this.lightbox.querySelector('.moment-controls__next')) === null || _a === void 0 ? void 0 : _a.getAttribute('href')) !== null && _b !== void 0 ? _b : "";
        this.prevLink = (_d = (_c = this.lightbox.querySelector('.moment-controls__prev')) === null || _c === void 0 ? void 0 : _c.getAttribute('href')) !== null && _d !== void 0 ? _d : "";
        this.closeLink = (_f = (_e = this.lightbox.querySelector('.moment-close')) === null || _e === void 0 ? void 0 : _e.getAttribute('href')) !== null && _f !== void 0 ? _f : "";
        this.addEventListeners();
    }
    addEventListeners() {
        if (!this.lightbox)
            return;
        this.lightbox.addEventListener('keydown', (e) => {
            switch (e.code) {
                case "ArrowLeft":
                    if (this.prevLink)
                        this.goTo(this.prevLink);
                    break;
                case "ArrowRight":
                    if (this.nextLink)
                        this.goTo(this.nextLink);
                    break;
                case "Escape":
                    if (this.closeLink)
                        this.goTo(this.closeLink);
                    break;
            }
        });
    }
    goTo(url) {
        window.location.href = url;
    }
}
document.addEventListener('DOMContentLoaded', () => {
    new MomentDate();
    new MomentLightbox();
});

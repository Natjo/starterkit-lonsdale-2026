/**
 * @module Slider
 * @param {HTMLElement} el 
 * 
 */

function Slider(slider) {
    const content = slider.querySelector(".slider-content");
    const items = slider.querySelectorAll(".item");
    const btn_next = slider.querySelector(".next");
    const btn_prev = slider.querySelector(".prev");
    let isDisable = false, isSwipe = false;
    let oldscrollLeft = 0, scrollLeft = 0;
    let gap = 0, left = 0, nb = 1;
    let index = 0, maxIndex = 0;
    let req, dateStart, itemWidth;

    content.scrollTo(0, 0);

    const controlStatus = () => {
        if (btn_prev) {
            btn_prev.setAttribute('aria-disabled', index <= 0 ? true : false);
            btn_prev.classList[isDisable ? 'add' : 'remove']('hide');
        }
        if (btn_next) {
            btn_next.setAttribute('aria-disabled', index == maxIndex ? true : false);
            btn_next.classList[isDisable ? 'add' : 'remove']('hide');
        }
    }


    const easeOutQuint = (t, b, c, d) => c * ((t = t / d - 1) * t * t * t * t + 1) + b;

    const fakeScrollTo = end => {
        controlStatus();
        cancelAnimationFrame(req);
        let init = null;
        let time;
        const start = content.scrollLeft;
        const duration = 900;
        const startAnim = timeStamp => {
            init = timeStamp;
            draw(timeStamp);
        }
        const draw = now => {
            time = now - init;
            content.scrollTo(easeOutQuint(time, start, end - start, duration), 0);
            req = requestAnimationFrame(draw);
            if (time >= duration) {
                cancelAnimationFrame(req);
                swipe(false);
            };
        }
        req = requestAnimationFrame(startAnim)
    };

    const goto = () => {
        if (index <= 0) index = 0;
        if (index >= maxIndex) {
            index = maxIndex;
            fakeScrollTo(content.scrollWidth - content.offsetWidth);
        }
        else {
            fakeScrollTo(items[index].offsetLeft - left);
        }
        //this.onchange(index, maxIndex, getPercent());
    }

    const getPercent = () => content.scrollLeft * 100 / (content.scrollWidth - content.offsetWidth);

    const swipe = status => {
        isSwipe = status;
        content.classList[status ? "add" : "remove"]('swipe');
    }

    const getMaxIndex = () => {
        maxIndex = 0;
        for (let i = 0; i < items.length - 1; i++) {
            if (items[i].offsetLeft < content.scrollWidth - content.offsetWidth + left) {
                maxIndex++;
            }
        }
    }

    const getIndex = () => {
        index = ~~((content.scrollLeft + (itemWidth / 2 + gap)) / (itemWidth + gap));
        if (content.scrollLeft + content.offsetWidth >= content.scrollWidth) index = maxIndex;

        this.onchange(index, maxIndex, getPercent());
    }

    const resize = () => {
        gap = parseInt(getComputedStyle(content).gap);
        nb = Number(getComputedStyle(content).getPropertyValue('--nb'));
        itemWidth = items[0].offsetWidth;
        const bound = slider.getBoundingClientRect();
        left = bound.left;
        slider.style.setProperty('--left', `${left}px`);
        slider.style.setProperty('--right', `${document.body.clientWidth - bound.right}px`);
        getMaxIndex();
        isDisable = content.scrollWidth <= content.offsetWidth ? true : false;
        slider.classList[isDisable ? 'add' : 'remove']('disable');
        controlStatus();
    };

    const onscroll = () => getIndex();

    const mouseMove = e => content.scroll(-e.clientX + scrollLeft, 0);

    const mouseUp = e => {
        window.removeEventListener('mousemove', mouseMove);
        window.removeEventListener('mouseup', mouseUp);
        content.classList.remove("grab");
        if (new Date() - dateStart < 300) {
            const diff = content.scrollLeft - oldscrollLeft;
            let dir = 0;
            if (diff > 4) dir = 1;
            if (diff < -4) dir = -1;
            index = index + dir;
        }
        oldscrollLeft = content.scrollLeft
        goto();
    };

    const mouseDown = (val, button) => {
        if (button != 0) return false;
        dateStart = new Date();
        oldscrollLeft = content.scrollLeft;
        scrollLeft = val + content.scrollLeft;
        content.classList.add("grab");
        cancelAnimationFrame(req);
        window.addEventListener('mousemove', mouseMove);
        window.addEventListener('mousemove', () => { swipe(true) }, { once: true });
        window.addEventListener('mouseup', mouseUp);
        return false;
    }

    const next = () => {
        swipe(true);
        index++;
        goto();
        this.onnext(index);
    };

    const prev = () => {
        swipe(true);
        index--;
        goto();
        this.onprev(index);
    };

    this.onnext = () => { }
    this.onprev = () => { }

    if (btn_next) btn_next.onclick = () => next();
    if (btn_prev) btn_prev.onclick = () => prev();

    this.onchange = () => {
    }

    this.add = () => {
        content.scrollTo(0, 0);
        slider.classList.add('slider');
        resize();
        window.addEventListener('load', () => {
            resize();
        })
        content.onmousedown = e => mouseDown(e.clientX, e.button);
        content.addEventListener('scroll', onscroll, { passive: true });
        window.addEventListener('resize', resize, { passive: true });
    }

    this.remove = () => {
        slider.classList.remove('slider');
        content.onmousedown = null;
        content.removeEventListener('scroll', onscroll);
        window.removeEventListener('resize', resize);
        mouseUp();
    }

    this.slideTo = (value) => {
        swipe(true);
        index = value;
        goto();
    }
}

export default Slider;
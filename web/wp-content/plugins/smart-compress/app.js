const main = document.getElementById('sc-main');
const nonce = main.getAttribute('data-nonce');
const ajax_url = main.getAttribute('data-ajaxurl');
const types = main.querySelectorAll('[name="sc-type"]');
const btn_generate = main.querySelector('.btn-generate');
const btn_purge = main.querySelector('.btn-purge');
const btn_assets = main.querySelector('.btn-assets');
const dialog = main.querySelector('.sc-dialog');
const progress = main.querySelector('#sc-progress');
const btn_pause = main.querySelector('.sc-btn.pause');
const btn_cancel = main.querySelector('.sc-btn.cancel');
const btn_close = main.querySelector('.sc-btn.close');
const btns_tip = main.querySelectorAll('.sc-btn-tips');
const sm_tips = main.querySelector('.sc-tips');

let quality;
let type;

/**
 * Quality
 * 
 */
const radios_quality = main.querySelectorAll('[name="sc-quality"]');


radios_quality.forEach(radio => {
    if (radio.checked) quality = radio.value;
    radio.onchange = () => {
        quality = radio.value;

        main.classList.add("loading");

        const data = new FormData();
        data.append('action', "sm_quality");
        data.append('nonce', nonce);
        data.append('value', quality);

        const xhr = new XMLHttpRequest();
        xhr.open("post", ajax_url, true);
        xhr.send(data);
        xhr.onload = () => {
            main.classList.remove("loading");
            location.reload();
        }
    }
});



/**
 * type d'upload
 * 
 */
const radios_type = main.querySelectorAll('[name="sc-type"]');
radios_type.forEach(radio => {
    if (radio.checked) type = radio.value;
    radio.onchange = () => {
        type = radio.value;
        const data = new FormData();
        data.append('action', "sm_type");
        data.append('nonce', nonce);
        data.append('value', type);
        const xhr = new XMLHttpRequest();
        xhr.open("post", ajax_url, true);
        xhr.send(data);
        xhr.onload = () => { }
        main.querySelector(".sc-generate").style.display = type == 1 ? "none" : "block";
    }
});

main.querySelector(".sc-generate").style.display = type == 1 ? "none" : "block";


/**
 * actions
 * 
 */

function Dialog() {
    btn_close.classList.add('hide');
    btn_pause.classList.remove('hide');
    btn_cancel.classList.remove('hide');
    let inc = 0;
    let ids;
    let length;
    let pause = false;
    let action1;

    function remove(id) {
        const data = new FormData();
        data.append('action', action1);
        data.append('nonce', nonce);
        data.append('id', id);
        const xhr = new XMLHttpRequest();
        xhr.open("post", ajax_url, true);
        xhr.send(data);
        xhr.onload = () => {
            if (!pause) {
                inc++;
                main.querySelector("#sc-progress-number").innerText = `${inc}/${length}`;
                progress.value = inc;
                if (inc < length) remove(ids[inc]);
                else myDialog.end();
            }
        }
    }

    btn_close.onclick = () => {
        pause = true;
        dialog.classList.remove('open')
    };

    btn_cancel.onclick = () => {
        pause = true;
        dialog.classList.remove('open')
    };

    btn_pause.onclick = () => {
        if (btn_pause.classList.contains("paused")) {
            btn_pause.innerText = "Pause";
            btn_pause.classList.remove("paused");
            pause = false;
            remove(ids[inc]);
        } else {
            btn_pause.innerText = "Reprendre";
            btn_pause.classList.add("paused");
            pause = true;
        }
    }

    this.open = (action, subaction, title) => {
        action1 = subaction;
        dialog.classList.add('open');
        dialog.querySelector(".title").innerText = title;
        pause = false;
        const data = new FormData(document.querySelector(".sc-generate"));
        data.append('action', action);
        data.append('nonce', nonce);

        const xhr = new XMLHttpRequest();
        xhr.open("post", ajax_url, true);

        xhr.send(data);
        xhr.onload = () => {
            const response = JSON.parse(xhr.responseText);
            console.log(response);
            ids = response.ids;
            length = ids.length;
            inc = 0;
            progress.value = 0;
            progress.max = length;
            remove(ids[inc]);
        }
    }

    this.end = () => {
        setTimeout(() => {
            btn_pause.classList.add('hide');
            btn_cancel.classList.add('hide');
            btn_close.classList.remove('hide');
            dialog.classList.remove('open');
        }, 2000)
    }
}

const myDialog = new Dialog();

btn_generate.onclick = () => myDialog.open("sm_generate", "sm_add", "Generate");

btn_purge.onclick = () => myDialog.open("sm_purge", "sm_remove", "Purge");

btn_assets.onclick = () => myDialog.open("sm_assets", "sm_add_assets", "Generate Assets");

/**
 * 
 */

function Tips() {
    let active;
    let btn_active;
    this.toggle = btn => {
        const page = btn.value;

        active = sm_tips.querySelector(`.sc-tips-panel.active`);
        btn_active = main.querySelector(`.sc-btn-tips.active`);

        if (active) {
            active.classList.add("close");
            active.addEventListener('animationend', () => {
                active.classList.remove("close");
                active.classList.remove("active");
            }, { once: true })
        }

        if (btn_active && btn_active != btn) {
            btn_active.classList.toggle("active");
        }

        btn.classList.toggle("active");
        sm_tips.querySelector(`[data-page="${page}"]`).classList.add('active');
    }
}

const myTip = new Tips();

btns_tip.forEach(btn => btn.onclick = () => myTip.toggle(btn))


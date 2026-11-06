/* Doppel: comportamento das páginas. JavaScript puro, sem dependências. */
(() => {
    'use strict';

    const $ = (selector, root = document) => root.querySelector(selector);
    const $$ = (selector, root = document) => Array.from(root.querySelectorAll(selector));
    const reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    /* Cabeçalho: menu no celular, sombra ao rolar e dropdown da conta */
    const header = $('#header');
    const toggle = $('[data-nav-toggle]');
    toggle?.addEventListener('click', () => {
        const open = toggle.getAttribute('aria-expanded') !== 'true';
        toggle.setAttribute('aria-expanded', String(open));
        header.classList.toggle('menu-open', open);
    });
    const onScroll = () => header?.classList.toggle('is-scrolled', window.scrollY > 10);
    window.addEventListener('scroll', onScroll, { passive: true });
    onScroll();

    document.addEventListener('click', (event) => {
        $$('details.dropdown[open]').forEach((menu) => {
            if (!menu.contains(event.target)) menu.removeAttribute('open');
        });
    });

    /* Modais de login/cadastro: sem JavaScript, os links abrem as páginas /entrar e /cadastrar */
    document.addEventListener('click', (event) => {
        const link = event.target.closest('[data-dialog]');
        const dialog = link && document.getElementById('dialog-' + link.dataset.dialog);
        if (!dialog || typeof dialog.showModal !== 'function') return;
        event.preventDefault();
        $$('dialog[open]').forEach((d) => d.close());
        dialog.showModal();
        $('input:not([type=hidden])', dialog)?.focus();
    });
    $$('dialog.modal').forEach((dialog) => {
        dialog.addEventListener('click', (event) => {
            if (event.target === dialog) dialog.close(); // clique fora da caixa
        });
    });

    /* Confirmações e senhas iguais */
    document.addEventListener('submit', (event) => {
        const form = event.target;
        if (form.dataset.confirm && !window.confirm(form.dataset.confirm)) {
            event.preventDefault();
        }
    });
    $$('[data-confirm-password]').forEach((form) => {
        const password = $('[name=password]', form);
        const confirmation = $('[name=password_confirm]', form);
        const check = () => confirmation.setCustomValidity(
            confirmation.value && confirmation.value !== password.value ? 'As senhas não conferem.' : ''
        );
        password.addEventListener('input', check);
        confirmation.addEventListener('input', check);
    });

    /* Carrossel de destaques */
    $$('[data-carousel]').forEach((carousel) => {
        const slides = $$('.carousel-slide', carousel);
        const dots = $$('[data-carousel-dot]', carousel);
        if (slides.length < 2) return;
        let current = 0;
        let timer = null;

        const show = (index) => {
            current = (index + slides.length) % slides.length;
            slides.forEach((slide, i) => {
                slide.hidden = i !== current;
                slide.classList.toggle('is-active', i === current);
            });
            dots.forEach((dot, i) => dot.setAttribute('aria-selected', String(i === current)));
        };
        const play = () => {
            if (reducedMotion) return;
            clearInterval(timer);
            timer = setInterval(() => show(current + 1), 7000);
        };
        const pause = () => clearInterval(timer);

        $('[data-carousel-next]', carousel)?.addEventListener('click', () => { show(current + 1); play(); });
        dots.forEach((dot) => dot.addEventListener('click', () => { show(Number(dot.dataset.carouselDot)); play(); }));
        carousel.addEventListener('mouseenter', pause);
        carousel.addEventListener('mouseleave', play);
        carousel.addEventListener('focusin', pause);
        carousel.addEventListener('focusout', play);
        play();
    });

    /* Chat das salas: busca mensagens novas a cada 3 s e envia sem recarregar a página */
    const chat = $('[data-chat]');
    if (chat) {
        const log = $('[data-chat-log]', chat);
        const form = $('[data-chat-form]', chat);
        const errorBox = $('[data-chat-error]', chat);
        let lastId = Number(chat.dataset.lastId) || 0;
        let busy = false;

        const scrollToEnd = () => { log.scrollTop = log.scrollHeight; };
        const showError = (message) => {
            if (!errorBox) return;
            errorBox.textContent = message || '';
            errorBox.hidden = !message;
        };
        const render = (messages) => {
            messages.forEach((m) => {
                if (m.id <= lastId) return;
                $('.chat-empty', log)?.remove();
                const item = document.createElement('li');
                item.className = `chat-msg chat-${m.kind}${m.mine ? ' chat-mine' : ''}`;
                item.dataset.id = m.id;
                if (m.kind !== 'system') {
                    const author = document.createElement('span');
                    author.className = 'chat-author';
                    author.textContent = m.author;
                    item.append(author);
                }
                const body = document.createElement('span');
                body.className = 'chat-body';
                body.textContent = m.body;
                const time = document.createElement('time');
                time.className = 'chat-time';
                time.textContent = m.time;
                item.append(body, time);
                log.append(item);
                lastId = m.id;
            });
            if (messages.length) scrollToEnd();
        };
        const request = async (options = {}) => {
            const response = await fetch(options.url || `${chat.dataset.url}?depois=${lastId}`, {
                headers: { Accept: 'application/json', 'X-CSRF-Token': chat.dataset.csrf },
                credentials: 'same-origin',
                ...options,
            });
            const data = await response.json().catch(() => ({}));
            if (!response.ok) throw new Error(data.error || 'Não foi possível falar com o servidor.');
            return data;
        };
        const poll = async () => {
            if (busy || document.hidden) return;
            busy = true;
            try {
                render((await request()).messages);
                showError('');
            } catch (error) {
                showError(error.message);
            } finally {
                busy = false;
            }
        };
        const send = async (text) => {
            const body = new FormData();
            body.append('message', text);
            body.append('after', String(lastId));
            const data = await request({ url: chat.dataset.url, method: 'POST', body });
            render(data.messages);
        };

        form?.addEventListener('submit', async (event) => {
            event.preventDefault();
            const input = $('[name=message]', form);
            const text = input.value.trim();
            if (!text) return;
            input.disabled = true;
            try {
                await send(text);
                input.value = '';
                showError('');
            } catch (error) {
                showError(error.message);
            } finally {
                input.disabled = false;
                input.focus();
            }
        });
        $$('[data-roll]', chat).forEach((button) => {
            button.addEventListener('click', () => send(button.dataset.roll).catch((e) => showError(e.message)));
        });

        scrollToEnd();
        setInterval(poll, 3000);
        document.addEventListener('visibilitychange', poll);
    }

    /* Criação de personagem: compra de pontos com bônus da espécie, modificadores e PV ao vivo */
    const pointBuy = $('[data-point-buy]');
    if (pointBuy) {
        const rules = JSON.parse($('[data-rules]', pointBuy).textContent);
        const speciesSelect = $('[data-species]', pointBuy);
        const classSelect = $('[data-class]', pointBuy);
        const weaponSelect = $('[data-weapon]', pointBuy);
        const rows = $$('[data-attr]', pointBuy);
        const pointsLeft = $('[data-points-left]', pointBuy);
        const summary = $('[data-summary]', pointBuy);
        const classHint = $('[data-class-hint]', pointBuy);
        const names = { for: 'Força', des: 'Destreza', con: 'Constituição', int: 'Inteligência', sab: 'Sabedoria', car: 'Carisma' };
        const modifier = (score) => Math.floor((score - 10) / 2);
        const signed = (n) => (n >= 0 ? '+' : '') + n;

        const update = () => {
            const bonuses = rules.species[speciesSelect.value] || {};
            const klass = rules.classes[classSelect.value];
            let spent = 0;
            let constitution = 10;

            rows.forEach((row) => {
                const attr = row.dataset.attr;
                const input = $('input', row);
                const base = Number(input.value);
                const valid = Number.isInteger(base) && base in rules.costs;
                spent += valid ? rules.costs[base] : 0;
                const bonus = Number(bonuses[attr] || 0);
                const final = (valid ? base : 8) + bonus;
                if (attr === 'con') constitution = final;
                $('[data-bonus]', row).textContent = bonus ? `+${bonus}` : '';
                $('[data-final]', row).textContent = `= ${final}`;
                $('[data-mod]', row).textContent = signed(modifier(final));
                row.classList.toggle('is-key', !!klass && (klass.primary === attr || klass.secondary === attr));
                input.setCustomValidity(valid ? '' : 'Use valores de 8 a 15.');
            });

            const left = rules.budget - spent;
            pointsLeft.textContent = left >= 0 ? `${left} pontos restantes` : `${-left} pontos acima do limite`;
            pointsLeft.classList.toggle('is-over', left < 0);
            if (left < 0) {
                $('input', rows[0]).setCustomValidity(`Você passou ${-left} pontos do limite de ${rules.budget}.`);
            }

            if (klass) {
                const hp = Math.max(1, klass.hitDie + modifier(constitution));
                summary.textContent = `Pontos de vida no nível 1: d${klass.hitDie} (${klass.hitDie}) ${signed(modifier(constitution))} de Constituição = ${hp} PV.`;
                classHint.textContent = `Atributos mais importantes: ${names[klass.primary]} e ${names[klass.secondary]}. ` +
                    (klass.martial ? 'Usa armas simples e marciais.' : 'Usa apenas armas simples.');
                $$('option[data-category]', weaponSelect).forEach((option) => {
                    option.disabled = option.dataset.category === 'marcial' ? !klass.martial : !klass.simple;
                });
                if (weaponSelect.selectedOptions[0]?.disabled) weaponSelect.value = '';
            } else {
                classHint.textContent = '';
            }
        };

        pointBuy.addEventListener('input', update);
        pointBuy.addEventListener('change', update);
        update();
    }
})();

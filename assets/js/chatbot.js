/**
 * Edluxury AI Concierge Chatbot
 * Premium support bot for UAE e-commerce customers
 */

document.addEventListener('DOMContentLoaded', function () {

    const launcher = document.getElementById('chatbot-launcher');
    const chatWindow = document.getElementById('chatbot-window');
    const closeBtn = document.getElementById('chatbot-close');
    const msgBox = document.getElementById('chatbot-messages');
    const inputField = document.getElementById('chatbot-input');
    const sendBtn = document.getElementById('chatbot-send');
    const qrContainer = document.getElementById('chatbot-quick-replies');
    const notifDot = document.getElementById('bot-notif-dot');

    if (!launcher) return; // Guard: element not found

    let isBotStarted = false;

    // ─── Inject Chatbot CSS ──────────────────────────────
    const style = document.createElement('style');
    style.textContent = `
        #chatbot-window { animation: chatSlideUp 0.35s cubic-bezier(0.25, 0.46, 0.45, 0.94); }
        @keyframes chatSlideUp {
            from { opacity: 0; transform: translateY(20px) scale(0.95); }
            to   { opacity: 1; transform: translateY(0) scale(1); }
        }
        @keyframes chatBounce {
            0%,100% { transform: scale(1); }
            50%      { transform: scale(1.12); }
        }
        @keyframes fadeInLeft {
            from { opacity: 0; transform: translateX(-12px); }
            to   { opacity: 1; transform: translateX(0); }
        }
        @keyframes fadeInRight {
            from { opacity: 0; transform: translateX(12px); }
            to   { opacity: 1; transform: translateX(0); }
        }
        @keyframes pulseRing {
            0%   { box-shadow: 0 0 0 0   rgba(15,61,62,0.60); }
            70%  { box-shadow: 0 0 0 14px rgba(15,61,62,0); }
            100% { box-shadow: 0 0 0 0   rgba(15,61,62,0); }
        }
        .chatbot-bubble          { max-width:82%; font-size:13.5px; line-height:1.55; padding:12px 16px; border-radius:18px; }
        .chatbot-bubble.bot      { background:#fff; color:#1a1a2e; border-bottom-left-radius:4px; animation:fadeInLeft .3s ease; box-shadow:0 2px 12px rgba(0,0,0,0.08); }
        .chatbot-bubble.user     { background:linear-gradient(135deg,#0F3D3E,#1a5f61); color:#fff; border-bottom-right-radius:4px; animation:fadeInRight .3s ease; }
        .chatbot-ts              { font-size:10px; opacity:0.5; margin-top:4px; text-align:right; }
        .qr-btn {
            font-size:12px; border:1.5px solid #0F3D3E; color:#0F3D3E; background:#fff;
            border-radius:50px; padding:5px 14px; white-space:nowrap; cursor:pointer;
            transition:all .2s ease;
        }
        .qr-btn:hover { background:#0F3D3E; color:#fff; }
        .dot-elastic {
            position:relative; width:7px; height:7px; border-radius:5px;
            background:#A69C63; animation:dotElastic 1s infinite;
            margin: 4px 12px;
        }
        .dot-elastic::before, .dot-elastic::after {
            content:''; position:absolute; top:0; width:7px; height:7px; border-radius:5px; background:#A69C63;
        }
        .dot-elastic::before { left:-12px; animation:dotElastic 1s infinite .25s; }
        .dot-elastic::after  { left: 12px; animation:dotElastic 1s infinite .5s; }
        @keyframes dotElastic {
            0%,80%,100% { transform:scale(0.8); opacity:0.5; }
            40%          { transform:scale(1.2); opacity:1; }
        }
        #chatbot-messages::-webkit-scrollbar { width:4px; }
        #chatbot-messages::-webkit-scrollbar-thumb { background:#ddd; border-radius:4px; }
        #chatbot-quick-replies::-webkit-scrollbar { display:none; }
    `;
    document.head.appendChild(style);

    // ─── Knowledge Base (10+ Responses) ─────────────────
    const KB = [
        {
            keys: ['hello', 'hi', 'hey', 'salam', 'مرحبا', 'assalam'],
            reply: "👋 Welcome to <strong>Edluxury</strong>! I'm your personal concierge. Whether you need help with an order, a product, or just want to explore our luxury collection — I'm here 24/7. How can I help you today?"
        },
        {
            keys: ['about', 'store', 'who', 'edluxury', 'كم', 'brand'],
            reply: "🏆 <strong>Edluxury</strong> is the UAE's premier curated luxury marketplace. We handpick exclusive, high-quality lifestyle products and deliver them directly to your doorstep across all 7 Emirates. Our mission is: <em>luxury made accessible.</em>"
        },
        {
            keys: ['real', 'fake', 'authentic', 'original', 'genuine', 'trust', 'legit'],
            reply: "💎 <strong>100% Authentic, Guaranteed.</strong> Every item on Edluxury passes a rigorous professional quality inspection before shipping. We have a strict zero-tolerance policy for imitations. Your trust is our most valued asset."
        },
        {
            keys: ['delivery', 'shipping', 'arrive', 'fast', 'how long', 'days', 'متى', 'توصيل'],
            reply: "🚚 We offer <strong>express delivery in 24-48 hours</strong> across Dubai, Abu Dhabi, and Sharjah. Delivery to Ajman, Ras Al Khaimah, Fujairah, and Umm Al Quwain takes 2-3 business days. We work with premium couriers for the best experience."
        },
        {
            keys: ['pay', 'payment', 'cod', 'cash', 'on delivery', 'how to pay', 'دفع', 'نقدي'],
            reply: "💵 We offer <strong>Cash on Delivery (COD)</strong> so you can fully inspect your item before paying. Your financial security is our priority — no payment is ever required upfront."
        },
        {
            keys: ['return', 'refund', 'exchange', 'change', 'wrong', 'defect', 'رجوع', 'استرجاع'],
            reply: "🔄 Not fully satisfied? No problem! We have a <strong>7-day hassle-free return policy</strong>. Simply contact us at edluxury32@gmail.com with your order number and we'll arrange a pickup at no cost to you."
        },
        {
            keys: ['track', 'order', 'status', 'where', 'my order', 'تتبع', 'طلبي'],
            reply: "📦 You can track your order anytime by visiting our <a href='/Edluxury/track-order.php' style='color:#0F3D3E;font-weight:bold;'>Order Tracking Page</a>. You'll need your <strong>Order Number</strong> and <strong>Email Address</strong> (which were sent to you in your confirmation email)."
        },
        {
            keys: ['price', 'expensive', 'cheap', 'discount', 'offer', 'sale', 'promo', 'سعر'],
            reply: "🏷️ Our prices are carefully set to reflect the true luxury quality of each item. We frequently run <strong>exclusive member offers and seasonal sales</strong>. For the latest deals, browse our <a href='/Edluxury/products.php' style='color:#0F3D3E;font-weight:bold;'>Products Page</a>!"
        },
        {
            keys: ['contact', 'email', 'call', 'reach', 'support', 'help', 'مساعدة', 'تواصل'],
            reply: "📧 Our executive support team is always ready to help! You can reach us at <strong>edluxury32@gmail.com</strong>. We typically respond within 2 hours during business hours (9AM–9PM GST)."
        },
        {
            keys: ['gift', 'wrap', 'packaging', 'box', 'gifting', 'هدية', 'تغليف'],
            reply: "🎁 Great choice for gifting! All Edluxury products come in <strong>premium, luxury packaging</strong> that is gift-ready right out of the box. It makes a wonderful impression — no extra wrapping needed!"
        },
        {
            keys: ['quality', 'material', 'how good', 'best', 'review', 'تقييم'],
            reply: "✨ Our customers consistently rate us <strong>5 stars</strong> for product quality. We only stock items that our own team has personally reviewed and approved. Read customer reviews on each product page for real feedback from UAE buyers!"
        },
        {
            keys: ['uae', 'dubai', 'abu dhabi', 'sharjah', 'emirates', 'ajman', 'كل الامارات'],
            reply: "🇦🇪 We proudly serve <strong>all 7 Emirates</strong> — Dubai, Abu Dhabi, Sharjah, Ajman, Ras Al Khaimah, Fujairah, and Umm Al Quwain. No matter where you are in the UAE, luxury is just a click away!"
        }
    ];

    const defaultReply = "That's a great question! 🤔 For the most accurate assistance, please email us at <strong>edluxury32@gmail.com</strong> and our concierge team will respond within 2 hours. Is there anything else I can tell you about our store?";

    // Quick reply chips shown to the user
    const quickReplies = [
        { label: "💎 Authenticity?", keys: ['real'] },
        { label: "🚚 Delivery time", keys: ['delivery'] },
        { label: "💵 How to pay", keys: ['pay'] },
        { label: "📦 Track order", keys: ['track'] },
        { label: "🔄 Return policy", keys: ['return'] },
        { label: "🏆 About Edluxury", keys: ['about'] }
    ];

    // ─── Helpers ─────────────────────────────────────────

    function ts() {
        return new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
    }

    function scrollBottom() {
        msgBox.scrollTop = msgBox.scrollHeight;
    }

    function addBotMsg(html) {
        const wrap = document.createElement('div');
        wrap.className = 'd-flex align-items-end gap-2 mb-3';
        wrap.innerHTML = `
            <div class="rounded-circle overflow-hidden flex-shrink-0 d-flex align-items-center justify-content-center"
                 style="width:32px;height:32px;background:linear-gradient(135deg,#0F3D3E,#A69C63);">
                <i class="bi bi-robot text-white" style="font-size:14px;"></i>
            </div>
            <div>
                <div class="chatbot-bubble bot">${html}</div>
                <div class="chatbot-ts">${ts()}</div>
            </div>`;
        msgBox.appendChild(wrap);
        scrollBottom();
    }

    function addUserMsg(text) {
        const wrap = document.createElement('div');
        wrap.className = 'd-flex justify-content-end mb-3';
        wrap.innerHTML = `
            <div>
                <div class="chatbot-bubble user">${text}</div>
                <div class="chatbot-ts" style="text-align:right;">${ts()}</div>
            </div>`;
        msgBox.appendChild(wrap);
        scrollBottom();
    }

    function showTyping(cb, delay = 1100) {
        const t = document.createElement('div');
        t.className = 'd-flex align-items-end gap-2 mb-3 typing-row';
        t.innerHTML = `
            <div class="rounded-circle overflow-hidden flex-shrink-0 d-flex align-items-center justify-content-center"
                 style="width:32px;height:32px;background:linear-gradient(135deg,#0F3D3E,#A69C63);">
                <i class="bi bi-robot text-white" style="font-size:14px;"></i>
            </div>
            <div class="bg-white rounded-4 shadow-sm px-3 py-2">
                <div class="dot-elastic"></div>
            </div>`;
        msgBox.appendChild(t);
        scrollBottom();
        setTimeout(() => { t.remove(); cb(); }, delay);
    }

    function findReply(text) {
        const lower = text.toLowerCase();
        for (const entry of KB) {
            if (entry.keys.some(k => lower.includes(k))) {
                return entry.reply;
            }
        }
        return defaultReply;
    }

    function renderQuickReplies() {
        qrContainer.innerHTML = '';
        quickReplies.forEach(qr => {
            const btn = document.createElement('button');
            btn.className = 'qr-btn';
            btn.innerText = qr.label;
            btn.onclick = () => {
                addUserMsg(qr.label);
                qrContainer.innerHTML = '';
                showTyping(() => {
                    addBotMsg(findReply(qr.keys[0]));
                    renderQuickReplies();
                });
            };
            qrContainer.appendChild(btn);
        });
    }

    function handleSend() {
        const text = inputField.value.trim();
        if (!text) return;
        addUserMsg(text);
        inputField.value = '';
        qrContainer.innerHTML = '';
        showTyping(() => {
            addBotMsg(findReply(text));
            renderQuickReplies();
        });
    }

    function openChat() {
        chatWindow.classList.remove('d-none');
        notifDot.style.display = 'none';
        launcher.style.animation = 'none';
        if (!isBotStarted) {
            isBotStarted = true;
            showTyping(() => {
                addBotMsg("👋 Welcome to <strong>Edluxury Concierge</strong>! I'm here to help you 24/7. Ask me anything about our store, delivery, authenticity, or orders.");
                showTyping(() => {
                    addBotMsg("Choose a quick topic below or type your question! 😊");
                    renderQuickReplies();
                }, 900);
            }, 800);
        }
        setTimeout(() => inputField.focus(), 300);
    }

    function closeChat() {
        // Animate out
        chatWindow.style.opacity = '0';
        chatWindow.style.transform = 'translateY(15px) scale(0.97)';
        setTimeout(() => {
            chatWindow.classList.add('d-none');
            chatWindow.style.opacity = '';
            chatWindow.style.transform = '';
        }, 250);
    }

    // ─── Events ──────────────────────────────────────────
    launcher.addEventListener('click', () => {
        chatWindow.classList.contains('d-none') ? openChat() : closeChat();
    });
    closeBtn.addEventListener('click', closeChat);
    sendBtn.addEventListener('click', handleSend);
    inputField.addEventListener('keypress', e => { if (e.key === 'Enter') handleSend(); });

    // Bounce launcher & show dot after 5 seconds to attract attention
    setTimeout(() => {
        if (chatWindow.classList.contains('d-none')) {
            notifDot.style.display = 'block';
            launcher.style.animation = 'chatBounce 0.7s ease 2, pulseRing 2s 0.7s infinite';
        }
    }, 5000);

});

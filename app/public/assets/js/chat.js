
(function () {
  const chatBox = document.getElementById('chatMessages');
  const newBtn = document.getElementById('newMessagesBtn');
  // try to find your chat form (fallback to first form)
  const chatForm = document.querySelector('form[wire\\:submit\\.prevent]') || document.querySelector('form');

  if (!chatBox || !newBtn) return;

  let userJustSent = false;
  let lastScrollHeight = chatBox.scrollHeight;
  const SCROLL_TOLERANCE = 80; // px: adjust if needed

  function isNearBottom(el, tol = SCROLL_TOLERANCE) {
    return (el.scrollHeight - el.scrollTop - el.clientHeight) <= tol;
  }

  function scrollChatToBottom(smooth = false) {
    chatBox.scrollTo({
      top: chatBox.scrollHeight,
      behavior: smooth ? 'smooth' : 'auto'
    });
    lastScrollHeight = chatBox.scrollHeight;
  }

  function showButton() {
    newBtn.classList.remove('hidden');
  }
  function hideButton() {
    newBtn.classList.add('hidden');
  }

  // On initial load: scroll to bottom
  document.addEventListener('livewire:init', () => {
    // slight delay to allow initial rendering
    setTimeout(() => scrollChatToBottom(false), 20);
  });

  // When the user submits (sends) a message: mark flag so we force-scroll on update
  if (chatForm) {
    chatForm.addEventListener('submit', () => {
      userJustSent = true;
      hideButton();
    }, { passive: true });
  }

  // When user manually scrolls: if they get to the bottom, hide the button
  chatBox.addEventListener('scroll', () => {
    if (isNearBottom(chatBox)) {
      hideButton();
    }
  });

  // Observe DOM changes inside chatBox (new messages appended)
  const observer = new MutationObserver(function (mutationsList) {
    // compute whether content height increased (new messages)
    const newHeight = chatBox.scrollHeight;
    const added = newHeight > lastScrollHeight;
    lastScrollHeight = newHeight;

    if (!added) return;

    if (isNearBottom(chatBox) || userJustSent) {
      // If user was at bottom (or just sent a message), scroll to bottom
      scrollChatToBottom(true);
      userJustSent = false;
      hideButton();
    } else {
      // otherwise show the "new messages" button
      showButton();
    }
  });

  observer.observe(chatBox, { childList: true, subtree: true });

  // Click the button to jump to latest
  newBtn.addEventListener('click', () => {
    scrollChatToBottom(true);
    hideButton();
  });

  // Backup: ensure on Livewire updates we still behave (in case mutation observer misses)
  document.addEventListener('livewire:update', () => {
    if (userJustSent || isNearBottom(chatBox)) {
      scrollChatToBottom(true);
      userJustSent = false;
      hideButton();
    }
  });
})();

document.addEventListener("livewire:init", () => {
    Livewire.on("messageSent", () => {
        document.getElementById("chatInput").value = "";
    });

    // 👇 always scroll to bottom when switching conversations
    Livewire.on("chatChanged", () => {
        const chatBox = document.getElementById("chatMessages");
        if (chatBox) {
            setTimeout(() => {
                chatBox.scrollTo({
                    top: chatBox.scrollHeight,
                    behavior: "auto"
                });
            }, 20);
        }
    });
});


function toggleSidebar() {
    const sidebar = document.getElementById('userSidebar');
    sidebar.classList.toggle('-translate-x-full');
}

document.addEventListener("click", (e) => {
  const bubble = e.target.closest("[data-message-id]");

  // Hide all timestamps first
  document.querySelectorAll("[id^='timestamp-']")
    .forEach(ts => ts.classList.add("hidden"));

  if (bubble) {
    // Show only the clicked one
    const id = bubble.dataset.messageId;
    const ts = document.getElementById("timestamp-" + id);
    if (ts) {
      ts.classList.remove("hidden");
    }
  }
});

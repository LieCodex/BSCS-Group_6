
function autoGrow(element) {
    element.style.height = "4rem";
    if (element.scrollHeight > element.clientHeight) {
        element.style.height = (element.scrollHeight) + "px";
    }
}

// Toggle 3 dots menu for comments
function toggleCommentMenu(commentId) {
    const menu = document.getElementById('comment-menu-' + commentId);
    if (menu) {
        menu.classList.toggle('hidden');
        document.querySelectorAll('[id^="comment-menu-"]').forEach(m => {
            if (m !== menu) m.classList.add('hidden');
        });
    }
}

// Hide menu when clicking outside
document.addEventListener('click', function(event) {
    document.querySelectorAll('[id^="comment-menu-"]').forEach(menu => {
        if (!menu.classList.contains('hidden') && !menu.contains(event.target) && !event.target.matches('button[onclick^="toggleCommentMenu"]')) {
            menu.classList.add('hidden');
        }
    });
});

function toggleReplyForm(commentId, show = null) {
    const replyForm = document.getElementById('reply-form-' + commentId);
    if (!replyForm) return;

    if (show === true) {
        replyForm.classList.remove('hidden');
    } else if (show === false) {
        replyForm.classList.add('hidden');
    } else {
        replyForm.classList.toggle('hidden');
    }
}

function autoGrow(element) {
    element.style.height = "16px";
    element.style.height = (element.scrollHeight)+"px";
}
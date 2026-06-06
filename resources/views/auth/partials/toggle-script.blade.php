<script>
function togglePassword(fieldId, btn) {
    const input = document.getElementById(fieldId);
    const open = btn.querySelector('.eye-open');
    const close = btn.querySelector('.eye-close');

    if (input.type === "password") {
        input.type = "text";
        if(open) open.classList.add("hidden");
        if(close) close.classList.remove("hidden");
    } else {
        input.type = "password";
        if(open) open.classList.remove("hidden");
        if(close) close.classList.add("hidden");
    }
}
</script>
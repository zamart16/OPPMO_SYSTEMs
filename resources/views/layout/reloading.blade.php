<script>
    window.addEventListener("pageshow", function (event) {
        const historyTraversal = event.persisted || 
            (typeof performance !== "undefined" && performance.getEntriesByType("navigation")[0]?.type === "back_forward");

        if (historyTraversal) {
            window.location.reload();
        }
    });
</script>

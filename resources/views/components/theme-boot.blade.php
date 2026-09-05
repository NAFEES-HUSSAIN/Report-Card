{{-- Anti-FOUC dark mode bootstrap — include as first script in <head> --}}
<script>
    (function () {
        try {
            const stored = localStorage.getItem('gradesphere-theme');
            const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
            const useDark = stored === 'dark' || (stored === null && prefersDark);
            document.documentElement.classList.toggle('dark', useDark);
        } catch (e) {
            // ignore storage errors
        }
    })();
</script>

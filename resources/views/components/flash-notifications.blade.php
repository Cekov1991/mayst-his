@if(session('success') || session('error'))
    <script>
        (function() {
            function showFlashNotifications() {
                @if(session('success'))
                    if (typeof showNotification !== 'undefined') {
                        showNotification('', {!! json_encode(session('success')) !!}, 'success', 3);
                    }
                @endif

                @if(session('error'))
                    if (typeof showNotification !== 'undefined') {
                        showNotification('', {!! json_encode(session('error')) !!}, 'error', 5);
                    }
                @endif
            }

            // Try immediately if showNotification exists, otherwise wait for DOM
            if (typeof showNotification !== 'undefined') {
                showFlashNotifications();
            } else {
                document.addEventListener('DOMContentLoaded', function() {
                    setTimeout(showFlashNotifications, 100);
                });
            }
        })();
    </script>
@endif


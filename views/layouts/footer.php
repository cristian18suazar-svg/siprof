            </div><!-- /page content -->
        </main>
    </div><!-- /flex wrapper -->

    <script>
        // Toggle sidebar en móvil
        function toggleSidebar() {
            const sidebar = document.querySelector('.sidebar-transition');
            if (sidebar) sidebar.classList.toggle('hidden');
        }

        // Animación de números para estadísticas
        function animateNumber(element, target, duration) {
            duration = duration || 1000;
            var start = 0;
            var increment = target / (duration / 16);
            var current = start;
            var timer = setInterval(function() {
                current += increment;
                if (current >= target) {
                    current = target;
                    clearInterval(timer);
                }
                element.textContent = Math.floor(current).toLocaleString();
            }, 16);
        }

        // Inicializar animaciones cuando el contenido esté cargado
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('[data-animate-number]').forEach(function(element) {
                var target = parseInt(element.dataset.animateNumber);
                animateNumber(element, target);
            });
        });
    </script>
</body>
</html>

//carrusel
var swiper = new Swiper('.swiper-container', {
    slidesPerView: 1, // Muestra una imagen a la vez
    spaceBetween: 0, // Sin espacio entre imágenes
    navigation: {
        nextEl: '.swiper-button-next',
        prevEl: '.swiper-button-prev',
    },
    pagination: {
        el: '.swiper-pagination',
        clickable: true,
    },
    loop: true, // Para hacer el carrusel circular
    autoplay: {
        delay: 3000, // Tiempo en milisegundos entre transiciones
    },
});




        // Ocultar la pantalla de carga cuando la página esté completamente cargada después de un retraso
        window.addEventListener('load', function() {
            setTimeout(function() {
                document.querySelector('.loader').classList.add('hidden');
                document.body.style.overflow = 'auto'; // Mostrar scroll nuevamente
            }, 1000); // 3000 milisegundos = 3 segundos
        });




        //slider de productos
        $(document).ready(function(){
            $('.related-products').slick({
                infinite: true,
                slidesToShow: 3, // Número de productos visibles en el slider
                slidesToScroll: 1,
                arrows: true, // Muestra las flechas de navegación
                responsive: [
                    {
                        breakpoint: 1024,
                        settings: {
                            slidesToShow: 2
                        }
                    },
                    {
                        breakpoint: 600,
                        settings: {
                            slidesToShow: 1
                        }
                    }
                ]
            });
        });
    


                // Código para el slider
                let slideIndex = 0;
                showSlides();
        
                function showSlides() {
                    let i;
                    const slides = document.getElementsByClassName("slide");
                    for (i = 0; i < slides.length; i++) {
                        slides[i].style.display = "none";
                    }
                    slideIndex++;
                    if (slideIndex > slides.length) {slideIndex = 1}
                    slides[slideIndex-1].style.display = "block";
                    setTimeout(showSlides, 3000); // Cambia cada 3 segundos
                }
        
                // Código para ocultar la pantalla de carga
                document.addEventListener("DOMContentLoaded", function() {
                    setTimeout(function() {
                        document.getElementById('loader').classList.add('hidden');
                    }, 1000); // Ocultar la pantalla de carga después de 1 segundo
                });
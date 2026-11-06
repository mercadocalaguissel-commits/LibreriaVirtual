// ========================================
// CARGAR PRODUCTOS DESDE MYSQL
// LIBRERÍA ESTUDIANTIL JP
// ========================================

document.addEventListener("DOMContentLoaded", cargarProductos);

async function cargarProductos() {

    const contenedor = document.querySelector(".productos-grid");

    try {

        const respuesta = await fetch("productos.php");

        if (!respuesta.ok) {
            throw new Error("No se pudieron cargar los productos.");
        }

        const productos = await respuesta.json();

        contenedor.innerHTML = "";

        productos
            .filter(producto => producto.categoria === "Útiles escolares")
            .forEach(producto => {

                const tarjeta = document.createElement("div");

                tarjeta.className = "producto-card";

                tarjeta.innerHTML = `

                    <img src="${producto.imagen}" alt="${producto.nombre}">

                    <div class="producto-info">

                        <h3>${producto.nombre}</h3>

                        <p>${producto.descripcion}</p>

                        <div class="precio">Bs. ${Number(producto.precio).toFixed(0)}</div>

                        <a href="#contacto" class="btn">
                            Pedir por formulario
                            <i class="fa-solid fa-clipboard-list"></i>
                        </a>

                        <button type="button" class="btn btn-carrito">
                            Agregar al carrito
                            <i class="fa-solid fa-cart-shopping"></i>
                        </button>

                    </div>

                `;

                contenedor.appendChild(tarjeta);

            });

    } catch (error) {

        console.error("Error al cargar productos:", error);

    }

}
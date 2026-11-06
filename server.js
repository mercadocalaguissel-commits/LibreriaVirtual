
require("dotenv").config();

const express = require("express");
const { Pool } = require("pg");
const cors = require("cors");

const app = express();
const PORT = 3000;

app.use(cors());
app.use(express.json());

app.use(express.static(__dirname));
app.get("/", (req, res) => {
    res.send("Servidor de Librería Estudiantil JP funcionando");
});

const path = require("path");

app.get("/utiles.html", (req, res) => {
    res.sendFile(path.join(__dirname, "utiles.html"));
});

const pool = new Pool({
    host: process.env.DB_HOST,
    port: process.env.DB_PORT,
    user: process.env.DB_USER,
    password: process.env.DB_PASSWORD,
    database: process.env.DB_NAME
});

// Comprobar la conexión con PostgreSQL
app.get("/api/estado", async (req, res) => {
    try {
        await pool.query("SELECT 1");
        res.json({
            conectado: true,
            mensaje: "Conexión con PostgreSQL exitosa"
        });
    } catch (error) {
        console.error(error.message);
        res.status(500).json({
            conectado: false,
            mensaje: "No se pudo conectar con PostgreSQL"
        });
    }
});

// Obtener los productos de la base de datos
app.get("/api/productos", async (req, res) => {
    try {
        const resultado = await pool.query(`
            SELECT
                p.id_producto,
                p.nombre,
                p.descripcion,
                p.precio,
                p.stock,
                c.nombre AS categoria,
                p.mostrar_categoria,
                p.mostrar_galeria
            FROM productos p
            JOIN categorias c
                ON p.id_categoria = c.id_categoria
            ORDER BY p.id_producto
        `);

        res.json(resultado.rows);
    } catch (error) {
        console.error(error.message);
        res.status(500).json({
            mensaje: "Error al consultar los productos"
        });
    }
});

app.listen(PORT, () => {
    console.log(`Servidor iniciado en http://localhost:${PORT}`);
});

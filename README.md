# Gerard's Order Management App

Projecte Individual 1 del mòdul PJ6 "Back-End amb tecnologia PHP i gestors de continguts" (CFGS Desenvolupament d'Aplicacions Web, Escola del Clot, curs 2026-27).

Aplicació de comerç electrònic per a una cadena de botigues d'electrònica d'àmbit europeu. Els comercials de l'empresa hi introdueixen les comandes dels clients i les poden consultar més endavant.

La part client (HTML, CSS i JavaScript) només fa d'interfície amb l'usuari. La part servidora (PHP) fa els càlculs, desa les comandes i envia les dades que es mostren.

## Productes

| Producte | Preu sense IVA |
| --- | ---: |
| Smartphone 6.1" 128 GB | 699,00 € |
| Laptop 15.6" 16 GB RAM 512 GB SSD | 899,00 € |
| Wireless noise cancelling headphones | 249,00 € |
| 55" 4K UHD Smart TV | 549,00 € |

El preu de cada comanda es calcula a la part servidora amb un IVA del 21%.

## Funcionalitats

1. **Inici de l'aplicació**: logo, nom de l'aplicació i botons d'accés a la documentació i a l'autenticació.
2. **Descripció i documentació**: explicació de l'aplicació i de cada operació del menú.
3. **Autenticació**: nom d'usuari i contrasenya. La part client comprova que no falti cap dada i la part servidora valida l'usuari amb `users.json`. Si l'usuari no és vàlid, es mostra un missatge d'error i es torna a l'inici.
4. **Menú d'operacions**: creació d'una comanda nova o visualització de comandes.
5. **Creació d'una comanda nova**: formulari amb les dades del client i els 4 productes. La part servidora calcula el preu amb IVA, retorna només el resultat i desa la comanda a `orders.json`.
6. **Visualització de comandes**: permet escollir entre veure totes les comandes o una sola.
7. **Visualització de totes les comandes**: taula amb totes les dades de totes les comandes.
8. **Visualització d'una comanda**: taula amb totes les dades de la comanda que té el codi demanat.

Quan no es troba o no es pot obrir `orders.json`, o quan no existeix la comanda demanada, la part servidora respon amb un `404 Not Found` i un document HTML amb el missatge d'error i els botons de tornada.

## Estructura de carpetes i fitxers

```
orderManagementApp/
├── .htaccess
├── .gitignore
├── README.md
├── TESTING.md
├── application
│   ├── client
│   │   ├── index.html            Inici de l'aplicació
│   │   ├── documentation.html    Descripció i documentació
│   │   ├── login.html / login.js Autenticació
│   │   ├── menu.html / menu.js   Menú d'operacions
│   │   ├── newOrder.html / newOrder.js       Creació d'una comanda nova
│   │   ├── viewOrders.html / viewOrders.js   Visualització de comandes
│   │   ├── viewOrder.html / viewOrder.js     Visualització d'una comanda
│   │   ├── styles.css
│   │   └── logo.svg
│   └── server
│       ├── functions.php         Funcions comunes
│       ├── login.php             Autenticació (POST)
│       ├── getProducts.php       Llista de productes (GET)
│       ├── createOrder.php       Creació d'una comanda (POST)
│       ├── showAllOrders.php     Totes les comandes (GET)
│       └── showOrder.php         Una comanda (GET)
├── orders
│   └── orders.json               Es crea automàticament amb la primera comanda
└── users
    └── users.json                Fitxer predefinit d'usuaris
```

## Requisits

- Servidor web Apache amb PHP 8 (per exemple, XAMPP).
- Mòduls d'Apache `ssl` i `rewrite` actius.
- Firefox o Chrome.

## Instal·lació i execució

1. Copia la carpeta `orderManagementApp` dins de la carpeta web d'Apache (`htdocs` a XAMPP).
2. Engega Apache des del panell de control d'XAMPP.
3. Obre l'aplicació amb HTTPS:

   ```
   https://localhost/orderManagementApp/
   ```

   El fitxer `.htaccess` redirigeix les peticions HTTP cap a HTTPS i porta l'arrel de l'aplicació a la interfície d'inici. També impedeix obrir les carpetes `users` i `orders` des del navegador.

4. La primera vegada el navegador avisarà que el certificat no és de confiança, perquè XAMPP fa servir un certificat propi. Cal acceptar-lo per continuar.

### HTTPS a Debian

```
sudo a2enmod ssl
sudo a2enmod rewrite
sudo a2ensite default-ssl
sudo systemctl restart apache2
```

Perquè el `.htaccess` funcioni, cal que la carpeta de l'aplicació tingui `AllowOverride All` a la configuració d'Apache.

## Usuari

L'usuari de l'aplicació és `admin`. La contrasenya es desa xifrada a `users/users.json` i la proporciona el professor.

## Proves

Les proves fetes i els resultats obtinguts són al fitxer [TESTING.md](TESTING.md).

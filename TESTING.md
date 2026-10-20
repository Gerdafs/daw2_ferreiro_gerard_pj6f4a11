# Proves de l'aplicació

Proves fetes el 20-10-2026 amb Apache 2.4.58, PHP 8.2.12 i OpenSSL 3.1.3 (XAMPP).

Les proves de la part servidora s'han fet amb peticions directes a `https://localhost/orderManagementApp/`. Les de la part client s'han fet al navegador.

Per a les proves d'autenticació correcta s'ha fet servir un usuari de prova creat només per provar. El fitxer `users.json` que s'entrega és l'original.

## Dades de prova

| Codi | Client | Productes | Total esperat amb IVA |
| --- | --- | --- | ---: |
| ORD-001 | Marta Puig Soler | 1 × Smartphone (699,00 €) + 2 × Smart TV (549,00 €) | (699 + 1.098) × 1,21 = 2.174,37 € |
| ORD-002 | Joan Ferrer Vidal | 3 × Headphones (249,00 €) | 747 × 1,21 = 903,87 € |

## 1. HTTPS i accés a les carpetes

| Núm. | Prova | Resultat esperat | Resultat obtingut |
| --- | --- | --- | --- |
| 1.1 | Obrir l'aplicació amb `http://` | Redirecció 301 a `https://` | Correcte |
| 1.2 | Obrir `https://localhost/orderManagementApp/` | Redirecció a la interfície d'inici | Correcte |
| 1.3 | Obrir `users/users.json` des del navegador | 403 Forbidden | Correcte |
| 1.4 | Obrir `orders/orders.json` des del navegador | 403 Forbidden | Correcte |

## 2. Inici i documentació

| Núm. | Prova | Resultat esperat | Resultat obtingut |
| --- | --- | --- | --- |
| 2.1 | Obrir la interfície d'inici | Es veuen el logo, el nom de l'aplicació i els botons de documentació i d'autenticació | Correcte |
| 2.2 | Obrir la documentació | Es veuen la descripció, l'explicació de cada operació i els botons de tornada a l'inici i d'autenticació | Correcte |

## 3. Autenticació

| Núm. | Prova | Resultat esperat | Resultat obtingut |
| --- | --- | --- | --- |
| 3.1 | Enviar sense usuari ni contrasenya | Missatge "Please enter both the username and the password." i no s'envia res al servidor | Correcte |
| 3.2 | Enviar només l'usuari | El mateix missatge i no s'envia res al servidor | Correcte |
| 3.3 | Enviar només la contrasenya | El mateix missatge i no s'envia res al servidor | Correcte |
| 3.4 | Enviar un usuari o una contrasenya incorrectes | El servidor respon `{"authenticated":false,"message":"The user could not be validated."}`. Es mostra "Authentication error: The user could not be validated." i es torna a l'inici | Correcte |
| 3.5 | Enviar un usuari i una contrasenya correctes | El servidor respon `{"authenticated":true}` i s'obre el menú d'operacions | Correcte |

## 4. Menú d'operacions

| Núm. | Prova | Resultat esperat | Resultat obtingut |
| --- | --- | --- | --- |
| 4.1 | Escollir "Create a new order" i clicar "Go" | S'obre la creació d'una comanda nova | Correcte |
| 4.2 | Escollir "View orders" i clicar "Go" | S'obre la visualització de comandes | Correcte |

## 5. Creació d'una comanda nova

| Núm. | Prova | Resultat esperat | Resultat obtingut |
| --- | --- | --- | --- |
| 5.1 | Obrir el formulari | Es veuen els 4 productes amb el preu i les quantitats deshabilitades | Correcte |
| 5.2 | Marcar i desmarcar el check d'un producte | La quantitat d'aquell producte s'habilita i es torna a deshabilitar | Correcte |
| 5.3 | Enviar sense cap producte marcat | Missatge "Please select at least one product." i no s'envia res al servidor | Correcte |
| 5.4 | Crear la comanda ORD-001 (primera comanda) | Es crea `orders.json`, el servidor respon només `2,174.37` i es mostra el total amb el text explicatiu | Correcte |
| 5.5 | Crear la comanda ORD-002 | El servidor respon `903.87` i la comanda s'afegeix a `orders.json` | Correcte |
| 5.6 | Comprovar `orders.json` | Conté totes les dades de les dues comandes i el preu amb IVA | Correcte |
| 5.7 | Crear una comanda sense la carpeta `orders` | 404 Not Found i document HTML amb l'error del fitxer i els 3 botons de tornada | Correcte |

## 6. Visualització de comandes

| Núm. | Prova | Resultat esperat | Resultat obtingut |
| --- | --- | --- | --- |
| 6.1 | Escollir "View all orders" i clicar "Go" | S'obre la taula de totes les comandes | Correcte |
| 6.2 | Escollir "View one order" i clicar "Go" | S'obre la cerca d'una comanda | Correcte |

## 7. Visualització de totes les comandes

| Núm. | Prova | Resultat esperat | Resultat obtingut |
| --- | --- | --- | --- |
| 7.1 | Veure totes les comandes sense `orders.json` | 404 Not Found i document HTML amb l'error del fitxer i els 3 botons de tornada | Correcte |
| 7.2 | Veure totes les comandes amb ORD-001 i ORD-002 desades | 200 OK i taula amb totes les dades de les dues comandes i els 3 botons de tornada | Correcte |

## 8. Visualització d'una comanda

| Núm. | Prova | Resultat esperat | Resultat obtingut |
| --- | --- | --- | --- |
| 8.1 | Cercar sense escriure cap codi | Missatge "Please enter the order ID." i no s'envia res al servidor | Correcte |
| 8.2 | Cercar ORD-001 sense `orders.json` | 404 Not Found i document HTML amb l'error del fitxer i els 3 botons de tornada | Correcte |
| 8.3 | Cercar ORD-002 | 200 OK i taula amb totes les dades de la comanda i els 3 botons de tornada | Correcte |
| 8.4 | Cercar ORD-999, que no existeix | 404 Not Found i document HTML amb el missatge "There is no order with the ID "ORD-999"." i els 3 botons de tornada | Correcte |

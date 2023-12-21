# Patiekalu-sarasas
## 1.	Sprendžiamo uždavinio aprašymas
### 1.1.	 Sistemos paskirtis 
Projekto tikslas yra palengvinti maisto pirkėjo darbą.

Sistema veikia tokiu principu: Web aplikacija bendradarbiauja su vartotoju, vartotojas bendadarbiauja su Web aplikacija ir API, o API bendradarbiauja su vartotoju ir duomenų baze.

Neregistruotas Vartotojas norėdamas naudotis sistema tiesiog ją įsijungs, pasirinks norimą restoraną iš sąrašo, tada iš menių sąrašo pasirinks sau norimą ir jam bus pateikti visi patiekalai iš to meniu.

Restorano savininkas galės prisijungti prie sistemos, kad galėtų pridėti naujus patiekalus į meniu arba net sukurti visiškai naujus meniu.

Administratorius galės patvirtinti naujus restoranus.
### 1.2	. Funkciniai reikalavimai
Registruotas vartotojas galės:
1.	Peržiūrėti restoranų, meniu, bei patiekalų sąrašus;
2.	Prisiregistruoti prie sistemos;
3.	Prisijungti prie sistemos.
4.	Atsijungti nuo sistemos;
   
Savininkas galės:
1.	Užpildyti prašyma restorano pridėjimui į sistemą;
2.	Keisti jau pridėto restorano informaciją;
3.	Trinti restoraną;
4.	Pridėti meniu prie savo restorano;
5.	Keisti savo restorano meniu;
6.	Pridėti naujus patiekalus į meniu;
7.	Keisti pridėtų patiekalų meniu;
   
Administratorius galės:
1.	Keisti esančių vartotojų informaciją;
2.	Patvirtinti pridėtus restoranus;
3.	Keisti restoranus;
4.	Keisti meniu;
5.	Keisti patiekalus;

## 2.	Sistemos architektūra
Sistemą sudaro šios dalys:

* Priekinė sistemos pusė (Front end) daroma naudojant Vue.js;
* Galinė sistemos pusė (Back end) daroma naudojant  Laravel;
* Duomenų bazė daroma naudojant MySQL;

Stai matome pavaizduotą kuriamos sistemos diegimo diagrama:
![image](https://github.com/GedvinasMockus/Patiekalu-sarasas/assets/126001610/de2e52e4-7a04-4b70-aa61-86513715a281)

## 3. Naudotojo sąsaja

|![image](https://github.com/GedvinasMockus/Patiekalu-sarasas/assets/126001610/80fad4cb-7ed3-40a1-b8d9-94d91121c200)|
| --- |
|<p align="center">*Pagrindinis puslapis, kai vartotojas neprisijungęs* </p>|

|![image](https://github.com/GedvinasMockus/Patiekalu-sarasas/assets/126001610/addb8fad-49dd-441e-8468-605236d350b3)|
| --- |
|<p align="center">*Pagrindinis puslapis, kai vartotojas prisijungęs* </p>|

|![image](https://github.com/GedvinasMockus/Patiekalu-sarasas/assets/126001610/cd7d6974-0bd4-4cb0-9b7a-3ae33ec7eb30)|
| --- |
|<p align="center">*Registracijos puslapis* </p>|
   
|![image](https://github.com/GedvinasMockus/Patiekalu-sarasas/assets/126001610/5c7b4575-5a7f-457b-8cc2-7202603fca38)|
| --- |
|<p align="center">*Prisijungimo puslapis* </p>|

|![image](https://github.com/GedvinasMockus/Patiekalu-sarasas/assets/126001610/3086152f-4dd2-4a85-8bf0-e38689d24943)|
| --- |
|<p align="center">*Restoranų sąrašo langas* </p>|

|![image](https://github.com/GedvinasMockus/Patiekalu-sarasas/assets/126001610/311dba45-a743-44b4-a08e-340a0c4a0ddc)|
| --- |
|<p align="center">*Restorano pridėjimo modalas* </p>|

|![image](https://github.com/GedvinasMockus/Patiekalu-sarasas/assets/126001610/be8f9b23-0f4d-40f9-9814-6270933ffded)|
| --- |
|<p align="center">*Restorano keitimo modalas* </p>|

|![image](https://github.com/GedvinasMockus/Patiekalu-sarasas/assets/126001610/8a1e77b9-e272-40c1-9a56-664c0e7ba778)|
| --- |
|<p align="center">*Restorano trinimo modalas* </p>|

|![image](https://github.com/GedvinasMockus/Patiekalu-sarasas/assets/126001610/b2997dea-7ac9-4b9c-9a8d-8e0654fa1aeb)|
| --- |
|<p align="center">*Meniu sąrašo langas* </p>|

|![image](https://github.com/GedvinasMockus/Patiekalu-sarasas/assets/126001610/1d40a4ae-d5b2-4ea8-8876-7c8746fc72d1)|
| --- |
|<p align="center">*Meniu pridėjimo modalas* </p>|

|![image](https://github.com/GedvinasMockus/Patiekalu-sarasas/assets/126001610/ed8f139d-fe47-4e12-8ab5-172cc886b65b)|
| --- |
|<p align="center">*Meniu keitimo modalas* </p>|

|![image](https://github.com/GedvinasMockus/Patiekalu-sarasas/assets/126001610/3db7d572-a5ec-4274-b6ae-90b36d7bffbe)|
| --- |
|<p align="center">*Meniu trinimo modalas* </p>|

|![image](https://github.com/GedvinasMockus/Patiekalu-sarasas/assets/126001610/0569dd12-a3f7-49f4-9202-4cc77591e738)|
| --- |
|<p align="center">*Patiekalų sąrašo langas* </p>|

|![image](https://github.com/GedvinasMockus/Patiekalu-sarasas/assets/126001610/e8571cc1-05a6-4b9d-8f80-2038ad2967cb)|
| --- |
|<p align="center">*Patiekalų pridėjimo modalas* </p>|

|![image](https://github.com/GedvinasMockus/Patiekalu-sarasas/assets/126001610/b6c8e824-1008-4dd4-ae86-1928a894feb1)|
| --- |
|<p align="center">*Patiekalų keitimo modalas* </p>|

|![image](https://github.com/GedvinasMockus/Patiekalu-sarasas/assets/126001610/2bc43e50-c22d-4e02-9b14-b76e3cf80469)|
| --- |
|<p align="center">*Patiekalų trinimo modalas* </p>|

|![image](https://github.com/GedvinasMockus/Patiekalu-sarasas/assets/126001610/991ce75a-7cf0-4e02-9d68-38543ec0e960)|
| --- |
|<p align="center">*Vartotojų sąrašo langas* </p>|

|![image](https://github.com/GedvinasMockus/Patiekalu-sarasas/assets/126001610/19d3826e-34e4-4e00-92b5-07d8a07bf4e4)|
| --- |
|<p align="center">*Vartotojų keitimo modalas* </p>|

## 4. API specifikacija
* Užklausos formatas: JSON
* Atsako formatas: JSON
* Autorizacija ir autentifikacija: JWT

### 4.1 Restoranų API metodai
<table>
    <tr>
        <td>Metodas:</td>
        <td>GET</td>
    </tr>
    <tr>
        <td>Paskirtis:</td>
        <td>Gauti restoranų sąrašą</td>
    </tr>
    <tr>
        <td>Adresas:</td>
        <td>/api/restaurant</td>
    </tr>
    <tr>
        <td>Atsako struktūra:</td>
        <td><pre>
        [
            {
                "id": 1,
                "name": "Kavine 1",
                "status": "Active",
                "owner": 6
            },
            {
                "id": 2,
                "name": "Kavine 2",
                "status": "Active",
                "owner": 9
            }
        ]
        </pre></td>
    </tr>
    <tr>
        <td>Atsakymo kodai:</td>
        <td>200 OK <br> 404 Not Found - Nerasta jokių restoranų</td>
    </tr>
</table>

<table>
    <tr>
        <td>Metodas:</td>
        <td></td>
    </tr>
    <tr>
        <td>Paskirtis:</td>
        <td></td>
    </tr>
    <tr>
        <td>Adresas:</td>
        <td>/api/</td>
    </tr>
    <tr>
        <td>Atsako struktūra:</td>
        <td></td>
    </tr>
    <tr>
        <td>Atsakymo kodai:</td>
        <td></td>
    </tr>
</table>

<table>
    <tr>
        <td>Metodas:</td>
        <td></td>
    </tr>
    <tr>
        <td>Paskirtis:</td>
        <td></td>
    </tr>
    <tr>
        <td>Adresas:</td>
        <td>/api/</td>
    </tr>
    <tr>
        <td>Atsako struktūra:</td>
        <td></td>
    </tr>
    <tr>
        <td>Atsakymo kodai:</td>
        <td></td>
    </tr>
</table>

<table>
    <tr>
        <td>Metodas:</td>
        <td></td>
    </tr>
    <tr>
        <td>Paskirtis:</td>
        <td></td>
    </tr>
    <tr>
        <td>Adresas:</td>
        <td>/api/</td>
    </tr>
    <tr>
        <td>Atsako struktūra:</td>
        <td></td>
    </tr>
    <tr>
        <td>Atsakymo kodai:</td>
        <td></td>
    </tr>
</table>

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

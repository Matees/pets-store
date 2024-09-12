# Pets Store Project

## Project Setup

To run the project

```bash
cd pets-store
docker compose up -d
```

Projekt sa skladá z dvoch oddelených častí: backend (BE) a frontend (FE).

Frontend je postavený na Vue.js. Je to veľmi jednoduchá implementácia, ktorá sa primárne zameriava na lepšiu orientáciu v API, takže som nekládol dôraz na vizuálnu stránku.
Backend je implementovaný pomocou základnej kostry Nette frameworku bez akýchkoľvek ďalších balíčkov.

Aplikácia sa dá ľahko rozšíriť alebo meniť podľa potreby. Snažil som sa štruktúru rozdeliť tak, aby mala logiku, použitím modelov, validátorov a repozitárov, čo umožňuje jednoduchú údržbu.

Napríklad, ak chcete upraviť model zvieratka, stačí upraviť model, pridať nový parameter do validátora a to by malo stačiť.


XML súbory sa ukladajú do samostatných súborov v adresári /app/data, kde je možné prezrieť aktuálny stav.
Obrázky sa ukladajú do priečinka www a zobrazujú sa pri dopyte na konkrétne zvieratko.

Kedze som nepouzil package tak niektore endpointy som musel trocha pozmenit aby sa medzi sebou nebili.
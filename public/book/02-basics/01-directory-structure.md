Adresářová struktura
====================

Takto vypadá ATK14 aplikace na disku.

    app/
            controllers/        místo pro kontrolery
            fields/             místo pro políčka formulářů
            forms/              místo pro formuláře
            helpers/            místo pro pomocné funkce zobrazování dat
            layouts/            místo pro layoutové šablony
            models/             místo pro modely
            views/              místo pro šablony
            widgets/            místo pro vzhledové třídy formulářových políček
    atk14/                      zdrojové kódy frameworku
    config/                     místo pro konfigurační soubory
            routers/            zde jsou routery pro sexy URL
    db/migrations/              místo pro databázové migrace
    lib/                        místo pro externí knihovny
    locale/                     místo pro lokalizační slovníky gettextu
    log/                        místo pro aplikační logy
    public/                     místo pro stylesheets, javascripty, obrázky a další statický obsah
    robots/                     zde sídlí roboti
            lock/               zde si roboti vytvářejí zámky pro zamezení konkurenčního běhu
    scripts/                    shellové skripty pro ovládání aplikace
    test/                       místo pro testovací třídy
    tmp/                        místo pro dočasné soubory

Na první pohled se těch adresářů zdá hodně. Nejedná se o klam - adresářů je skutečně hodně :)
Jakmile však začnete s ATK14 vyvíjet, velmi rychle se začnete orientovat. Nakonec zjistíte, že nejvíce času trávíte jen ve čtyřech adresářích.

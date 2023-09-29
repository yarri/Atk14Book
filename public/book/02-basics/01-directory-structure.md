Adresářová struktura
====================

Takto vypadá ATK14 aplikace na disku.

    app/
        controllers/            místo pro kontrolery
        fields/                 místo pro políčka formulářů
        forms/                  místo pro formuláře
        helpers/                místo pro pomocné funkce zobrazování dat
        layouts/                místo pro layoutové šablony
        models/                 místo pro modely
        views/                  místo pro šablony
            shared/             sdílené šablony napříč kontrolery
        widgets/                místo pro vzhledové třídy formulářových políček
    atk14/                      zdrojové kódy frameworku
    config/                     místo pro konfigurační soubory
        routers/                zde jsou routery pro sexy URL
    db/migrations/              místo pro databázové migrace
    lib/                        místo pro externí knihovny
    local_config/               adresář pro lokální konfigurační soubory, které se neverzují v gitu
    local_scripts/              shellové skripty pro ovládání této kontkrétní aplikace
    locale/                     místo pro lokalizační slovníky gettextu
    log/                        místo pro aplikační logy
    public/                     místo pro stylesheets, javascripty, obrázky a další statický obsah
    robots/                     zde sídlí roboti
        lock/                   zde si roboti vytvářejí zámky pro zamezení konkurenčního běhu
    scripts/                    shellové skripty pro ovládání aplikace
    test/                       místo pro testovací třídy
        app/                    základní aplikační testy
        controllers/            testy pro kontrolery
        fields/                 testy pro formulářová políčka
        fixtures/               sady testovacích dat
        models/                 testy pro modely
        lib/                    testy pro modely
        helpers/                testy pro zobrazovací pomocníky používané v šablonách
        routers/                testy pro vaše routery
    tmp/                        místo pro dočasné soubory
    vendor/                     adresář pro knihovny nainstalované pomocí nástroje composer

Na první pohled se těch adresářů zdá hodně. Nejedná se o klam &mdash; adresářů je skutečně hodně :) Brzy ale zjistíte, že nejvíce času budete trávít ve čtyřech adresářích.

Komplexní aplikace zpravidla obsahuje i několik tzv. namespaces. Jedná se o jakési podaplikace, které s hlavní aplikací sdílí databázi, modely, sdílené šablony, formulářová políčka a zobrazovací helpery.
Typický namespace je např. _admin_ &mdash; do adminu se přihlašuje administrátor a obsluhuje zde nástroje pro správu aplikace. Když chcete vytvořit namespace admin ve své aplikaci, začnete vytvořením nových adresářů.

    app/controllers/admin/
    app/forms/admin/
    app/views/admin/
    test/controllers/admin/

Dalším oblíbeným namespacem je _api_, kam se umísťují funkce pro strojovou komunikaci s okolním světem.

Důležité konfigurační soubory
-----------------------------

    config/
        settings.php            hlavní konfigurační soubor pro aplikaci
        locale.yml              seznam podporovaných jazyků (locales) v aplikaci
        deploy.yml              konfigurační soubor pro nasazování aplikace do produkce
        database.yml            přístupové údaje do databáze
